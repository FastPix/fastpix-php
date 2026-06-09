#!/usr/bin/env tsx
/*
 * Non-GET endpoints validator (POST / PUT / PATCH / DELETE) using `openapi-response-validator`.
 *
 * Unlike the GET validator, these operations MUTATE live data, so we cannot hit
 * the raw API and the SDK separately (that would create/delete twice). Instead
 * this driver invokes the PHP SDK once per operation and:
 *  - captures the SDK's deserialized return value (for the diff + artifact)
 *  - captures the raw HTTP status + raw JSON body from the SDK's underlying
 *    response (for OpenAPI response-schema validation)
 *
 * No fixtures are required. The driver runs a create -> use -> delete lifecycle:
 *  1. CREATE phase  (POST)   - creates real resources, captures their IDs
 *  2. UPDATE phase  (PUT/PATCH) - exercises updates against the created IDs
 *  3. DELETE phase  (DELETE) - tears the resources down, LAST, so deletes only
 *     run after every POST/PUT/PATCH has completed.
 *
 * A step whose required IDs were never captured (because an upstream create
 * failed) is reported as SKIP rather than called with nulls.
 *
 * Output:
 *  - per-operation artifacts in `tests/artifacts-non-get/`
 *  - `tests/NON_GET_ENDPOINTS_VALIDATION_REPORT.md`
 *
 * Requirements:
 *  - FASTPIX_USERNAME / FASTPIX_PASSWORD env vars (Basic Auth)
 *  - optional FASTPIX_BASE_URL / FASTPIX_SERVER_URL (defaults to spec server)
 */

/// <reference path="./shims.d.ts" />

import { readFileSync, writeFileSync, existsSync, mkdirSync, unlinkSync } from "node:fs";
import { tmpdir } from "node:os";
import { spawnSync } from "node:child_process";
import { join, dirname } from "node:path";
import { fileURLToPath } from "node:url";
import { createRequire } from "node:module";
import { randomUUID } from "node:crypto";
import yaml from "js-yaml";

const require = createRequire(import.meta.url);
const openapiResponseValidatorMod = require("openapi-response-validator");
const OpenAPIResponseValidator =
  openapiResponseValidatorMod?.default ?? openapiResponseValidatorMod;

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const ARTIFACTS_DIRNAME = "artifacts-non-get";
const REPORT_MD = "NON_GET_ENDPOINTS_VALIDATION_REPORT.md";
const MAX_PREVIEW_CHARS = 4000;

type Phase = "CREATE" | "UPDATE" | "DELETE";

type EndpointInfo = {
  path: string;
  method: string;
  operationId: string;
  responses: Record<string, any>;
};

// Mutable context threaded through the lifecycle; populated by capture() hooks.
type Ctx = {
  signingKeyId?: string;
  playlistId?: string;
  streamId?: string;
  mediaId?: string;
  mediaPlaybackId?: string; // the media's default playback id (ready when media is Ready)
  createdPlaybackId?: string; // a playback id created via create-media-playback-id
  trackId?: string;
  streamPlaybackId?: string;
  simulcastId?: string;
  uploadId?: string;
};

type Step = {
  operationId: string;
  phase: Phase;
  // ctx keys that must be present, else the step is skipped
  needs?: (keyof Ctx)[];
  // build the per-call request (path params) from the current ctx
  request: (ctx: Ctx) => Record<string, any>;
  // extract a created id from the SDK response value into ctx
  capture?: (value: any, ctx: Ctx) => void;
  // if the call fails with an error containing this substring, wait and retry
  // (used to wait for an async resource — e.g. a playback id — to become ready)
  retryOn?: string;
};

type StepResult = {
  operationId: string;
  method: string;
  path: string;
  phase: Phase;
  status: "PASS" | "FAIL" | "SKIP";
  httpStatus: number | null;
  openapiValid: boolean | null;
  openapiErrors: any[];
  sdkOk: boolean;
  sdkError?: string;
  missingInSDK: string[];
  missingInAPI: string[];
  note?: string;
  capturedId?: string;
};

type PHPSDKResult =
  | { ok: true; value: any; statusCode: number | null; rawBody: any }
  | { ok: false; error: { name?: string; message?: string; statusCode?: number; bodyJson?: any } };

function safeFileSlug(input: string): string {
  return input.replace(/[^a-zA-Z0-9_.-]+/g, "_");
}

function toPrettyJson(value: unknown): string {
  return JSON.stringify(value, null, 2);
}

function preview(text: string): string {
  return text.length > MAX_PREVIEW_CHARS ? `${text.slice(0, MAX_PREVIEW_CHARS)}\n... [truncated]` : text;
}

function basicAuthHeader(username: string, password: string): string {
  return "Basic " + Buffer.from(`${username}:${password}`).toString("base64");
}

const sleep = (ms: number) => new Promise((r) => setTimeout(r, ms));

// A freshly-created media is "Preparing"; adding playback-ids / tracks to it
// returns 400 until it reaches "Ready". Poll the GET media endpoint so the
// dependent create steps have a usable resource.
async function waitForMediaReady(
  baseUrl: string,
  username: string,
  password: string,
  mediaId: string,
  timeoutMs = 180000,
  intervalMs = 5000,
): Promise<string> {
  const url = `${baseUrl.replace(/\/$/, "")}/on-demand/${mediaId}`;
  const deadline = Date.now() + timeoutMs;
  let last = "unknown";
  while (Date.now() < deadline) {
    try {
      const res = await fetch(url, { headers: { Accept: "application/json", Authorization: basicAuthHeader(username, password) } });
      const body: any = await res.json().catch(() => null);
      last = body?.data?.status ?? last;
      if (last === "Ready") return "Ready";
      if (last === "Errored" || last === "Failed") return last;
    } catch {
      /* transient; keep polling */
    }
    await sleep(intervalMs);
  }
  return last;
}

// ---------------------------------------------------------------------------
// PHP SDK invocation
// ---------------------------------------------------------------------------

function invokePHPSDK(
  operationId: string,
  request: Record<string, any>,
  baseUrl: string,
  username: string,
  password: string,
): PHPSDKResult {
  const vendorAutoload = join(__dirname, "../vendor/autoload.php");

  const phpCode = String.raw`<?php
declare(strict_types=1);

require_once ${JSON.stringify(vendorAutoload)};

use FastPix\Sdk\Fastpixsdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');
error_reporting(E_ALL);

try {

function to_jsonable($x) {
    if ($x === null) {
        return null;
    }
    if ($x instanceof \DateTime || $x instanceof \DateTimeInterface) {
        $formatted = $x->format('Y-m-d\TH:i:s.u');
        $timezone = $x->getTimezone();
        if ($timezone->getName() === 'UTC' || $timezone->getName() === '+00:00' || $timezone->getOffset($x) === 0) {
            return $formatted . 'Z';
        }
        return $formatted . $timezone->format('P');
    }
    if (is_object($x) && ($x instanceof \BackedEnum)) {
        return $x->value;
    }
    if (is_array($x)) {
        return array_map('to_jsonable', $x);
    }
    if (is_object($x)) {
        if (method_exists($x, 'toArray')) {
            return to_jsonable($x->toArray());
        }
        $arr = [];
        $ref = new \ReflectionObject($x);
        foreach (get_object_vars($x) as $k => $v) {
            $name = $k;
            try {
                $prop = $ref->getProperty($k);
                $attrs = $prop->getAttributes('FastPix\\Sdk\\Serializer\\Annotation\\SerializedName');
                if (! empty($attrs)) {
                    $args = $attrs[0]->getArguments();
                    if (! empty($args)) {
                        $name = $args[0];
                    }
                }
            } catch (\ReflectionException $e) {
            }
            $arr[$name] = to_jsonable($v);
        }
        return $arr;
    }
    return $x;
}

function normalize_err($e) {
    $out = [
        'name' => get_class($e),
        'message' => $e->getMessage(),
        'stack' => $e->getTraceAsString(),
    ];
    if (property_exists($e, 'statusCode')) {
        $out['statusCode'] = $e->statusCode;
    } elseif (method_exists($e, 'getStatusCode')) {
        $out['statusCode'] = $e->getStatusCode();
    }
    if (property_exists($e, 'body')) {
        $body = $e->body;
        $out['body'] = $body;
        if (is_string($body)) {
            $decoded = json_decode($body, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $out['bodyJson'] = $decoded;
            }
        }
    } elseif (method_exists($e, 'getBody')) {
        $body = $e->getBody();
        $out['body'] = $body;
        if (is_string($body)) {
            $decoded = json_decode($body, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $out['bodyJson'] = $decoded;
            }
        }
    }
    return $out;
}

$payload = json_decode(file_get_contents('php://stdin'), true);
$op = $payload['operationId'] ?? '';
$req = $payload['request'] ?? [];
$base_url = $payload['baseUrl'] ?? null;
$username = $payload['username'] ?? '';
$password = $payload['password'] ?? '';

try {
    $sdk = Fastpixsdk::builder()
        ->setSecurity(new Components\Security(username: $username, password: $password));
    if ($base_url !== null && $base_url !== '') {
        $sdk = $sdk->setServerUrl($base_url);
    }
    $sdk = $sdk->build();

    $g = function($k) use ($req) { return $req[$k] ?? null; };
    $res = null;

    // ---------------- POST (create) ----------------
    if ($op === 'create-media') {
        $res = $sdk->inputVideo->createMedia(new Components\CreateMediaRequest(
            inputs: [new Components\PullVideoInput()],
            metadata: ['source' => 'sdk-validate']
        ));
    } elseif ($op === 'create_signing_key') {
        $res = $sdk->signingKeys->createSigningKey();
    } elseif ($op === 'create-a-playlist') {
        $res = $sdk->playlist->createAPlaylist(new Components\CreatePlaylistRequestManual(
            name: 'sdk-validate-playlist',
            referenceId: 'sdkvalidate' . uniqid(),
            type: Components\CreatePlaylistRequestManualType::Manual
        ));
    } elseif ($op === 'create-new-stream') {
        $res = $sdk->startLiveStream->createNewStream(new Components\CreateLiveStreamRequest(
            playbackSettings: new Components\PlaybackSettings(),
            inputMediaSettings: new Components\InputMediaSettings(metadata: ['name' => 'sdk-validate'])
        ));
    } elseif ($op === 'create-media-playback-id') {
        $res = $sdk->playback->createMediaPlaybackId(
            new Operations\CreateMediaPlaybackIdRequestBody(accessPolicy: Components\AccessPolicy::Public),
            $g('mediaId')
        );
    } elseif ($op === 'Add-media-track') {
        $res = $sdk->manageVideos->addMediaTrack(
            new Operations\AddMediaTrackRequestBody(tracks: new Components\AddTrackRequest()),
            $g('mediaId')
        );
    } elseif ($op === 'Generate-subtitle-track') {
        $res = $sdk->manageVideos->generateSubtitleTrack(
            new Components\TrackSubtitlesGenerateRequest(),
            $g('mediaId'),
            $g('trackId')
        );
    } elseif ($op === 'create-playbackId-of-stream') {
        $res = $sdk->livePlayback->createPlaybackIdOfStream(new Components\PlaybackIdRequest(), $g('streamId'));
    } elseif ($op === 'create-simulcast-of-stream') {
        $res = $sdk->simulcastStream->createSimulcastOfStream(
            new Components\SimulcastRequest(url: 'rtmp://example.com/live', streamKey: 'sk-' . uniqid()),
            $g('streamId')
        );
    } elseif ($op === 'direct-upload-video-media') {
        $res = $sdk->inputVideo->directUploadVideoMedia(new Operations\DirectUploadVideoMediaRequest(
            pushMediaSettings: new Operations\PushMediaSettings(metadata: ['source' => 'sdk-validate'])
        ));

    // ---------------- PUT / PATCH (update) ----------------
    } elseif ($op === 'updated-media') {
        $res = $sdk->manageVideos->updatedMedia(
            new Operations\UpdatedMediaRequestBody(metadata: ['updated' => 'true'], title: 'SDK Validate Title'),
            $g('mediaId')
        );
    } elseif ($op === 'updated-source-access') {
        $res = $sdk->manageVideos->updatedSourceAccess(
            new Operations\UpdatedSourceAccessRequestBody(sourceAccess: true),
            $g('mediaId')
        );
    } elseif ($op === 'updated-mp4Support') {
        $res = $sdk->manageVideos->updatedMp4Support(
            new Operations\UpdatedMp4SupportRequestBody(),
            $g('mediaId')
        );
    } elseif ($op === 'update-media-summary') {
        $res = $sdk->inVideoAIFeatures->updateMediaSummary(
            new Operations\UpdateMediaSummaryRequestBody(generate: true),
            $g('mediaId')
        );
    } elseif ($op === 'update-media-chapters') {
        $res = $sdk->inVideoAIFeatures->updateMediaChapters(
            new Operations\UpdateMediaChaptersRequestBody(chapters: true),
            $g('mediaId')
        );
    } elseif ($op === 'update-media-named-entities') {
        $res = $sdk->inVideoAIFeatures->updateMediaNamedEntities(
            new Operations\UpdateMediaNamedEntitiesRequestBody(namedEntities: true),
            $g('mediaId')
        );
    } elseif ($op === 'update-media-moderation') {
        $res = $sdk->inVideoAIFeatures->updateMediaModeration(
            new Operations\UpdateMediaModerationRequestBody(moderation: new Operations\UpdateMediaModerationModeration()),
            $g('mediaId')
        );
    } elseif ($op === 'update-media-track') {
        $res = $sdk->manageVideos->updateMediaTrack(
            new Components\UpdateTrackRequest(),
            $g('trackId'),
            $g('mediaId')
        );
    } elseif ($op === 'update-domain-restrictions') {
        $res = $sdk->playback->updateDomainRestrictions(
            new Operations\UpdateDomainRestrictionsRequestBody(allow: ['example.com']),
            $g('mediaId'),
            $g('playbackId')
        );
    } elseif ($op === 'update-user-agent-restrictions') {
        $res = $sdk->playback->updateUserAgentRestrictions(
            new Operations\UpdateUserAgentRestrictionsRequestBody(allow: ['Mozilla']),
            $g('mediaId'),
            $g('playbackId')
        );
    } elseif ($op === 'update-a-playlist') {
        $res = $sdk->playlist->updateAPlaylist(
            new Components\UpdatePlaylistRequest(name: 'SDK Validate Updated', description: 'updated by validator'),
            $g('playlistId')
        );
    } elseif ($op === 'add-media-to-playlist') {
        $res = $sdk->playlist->addMediaToPlaylist(
            new Components\MediaIdsRequest(mediaIds: [$g('mediaId')]),
            $g('playlistId')
        );
    } elseif ($op === 'change-media-order-in-playlist') {
        $res = $sdk->playlist->changeMediaOrderInPlaylist(
            new Components\MediaIdsRequest(mediaIds: [$g('mediaId')]),
            $g('playlistId')
        );
    } elseif ($op === 'update-live-stream') {
        $res = $sdk->manageLiveStream->updateLiveStream(
            new Components\PatchLiveStreamRequest(metadata: ['updated' => 'true'], reconnectWindow: 120),
            $g('streamId')
        );
    } elseif ($op === 'update-specific-simulcast-of-stream') {
        $res = $sdk->simulcastStream->updateSpecificSimulcastOfStream(
            new Components\SimulcastUpdateRequest(isEnabled: false),
            $g('streamId'),
            $g('simulcastId')
        );
    } elseif ($op === 'enable-live-stream') {
        $res = $sdk->manageLiveStream->enableLiveStream($g('streamId'));
    } elseif ($op === 'disable-live-stream') {
        $res = $sdk->manageLiveStream->disableLiveStream($g('streamId'));
    } elseif ($op === 'complete-live-stream') {
        $res = $sdk->manageLiveStream->completeLiveStream($g('streamId'));
    } elseif ($op === 'cancel-upload') {
        $res = $sdk->manageVideos->cancelUpload($g('uploadId'));

    // ---------------- DELETE ----------------
    } elseif ($op === 'delete-media-from-playlist') {
        $res = $sdk->playlist->deleteMediaFromPlaylist(
            new Components\MediaIdsRequest(mediaIds: [$g('mediaId')]),
            $g('playlistId')
        );
    } elseif ($op === 'delete-a-playlist') {
        $res = $sdk->playlist->deleteAPlaylist($g('playlistId'));
    } elseif ($op === 'delete-media-track') {
        $res = $sdk->manageVideos->deleteMediaTrack($g('mediaId'), $g('trackId'));
    } elseif ($op === 'delete-media-playback-id') {
        $res = $sdk->playback->deleteMediaPlaybackId($g('mediaId'), $g('playbackId'));
    } elseif ($op === 'delete-simulcast-of-stream') {
        $res = $sdk->simulcastStream->deleteSimulcastOfStream($g('streamId'), $g('simulcastId'));
    } elseif ($op === 'delete-playbackId-of-stream') {
        $res = $sdk->livePlayback->deletePlaybackIdOfStream($g('streamId'), $g('playbackId'));
    } elseif ($op === 'delete-live-stream') {
        $res = $sdk->manageLiveStream->deleteLiveStream($g('streamId'));
    } elseif ($op === 'delete-media') {
        $res = $sdk->manageVideos->deleteMedia($g('mediaId'));
    } elseif ($op === 'delete_signing_key') {
        $res = $sdk->signingKeys->deleteSigningKey($g('signingKeyId'));
    } else {
        echo json_encode(['ok' => false, 'error' => ['name' => 'SDKMappingError', 'message' => 'No PHP SDK mapping for ' . $op]]);
        exit(0);
    }

    $statusCode = property_exists($res, 'statusCode') ? $res->statusCode : null;
    $rawBody = null;
    if (property_exists($res, 'rawResponse') && $res->rawResponse !== null) {
        try { $rawBody = (string) $res->rawResponse->getBody(); } catch (\Throwable $t) { $rawBody = null; }
    }

    $actualResponse = null;
    $metadataProps = ['contentType', 'statusCode', 'rawResponse', 'defaultError', 'error'];
    foreach (['object', 'array', 'string', 'mixed'] as $prop) {
        if (property_exists($res, $prop) && $res->$prop !== null) { $actualResponse = $res->$prop; break; }
    }
    if ($actualResponse === null) {
        foreach (get_object_vars($res) as $propName => $propValue) {
            if (in_array($propName, $metadataProps)) { continue; }
            if ($propValue !== null) { $actualResponse = $propValue; break; }
        }
    }
    if ($actualResponse === null) { $actualResponse = $res; }

    echo json_encode([
        'ok' => true,
        'value' => to_jsonable($actualResponse),
        'statusCode' => $statusCode,
        'rawBody' => $rawBody,
    ], JSON_UNESCAPED_SLASHES);
} catch (\Exception $e) {
    echo json_encode(['ok' => false, 'error' => normalize_err($e)]);
} catch (\Error $e) {
    echo json_encode(['ok' => false, 'error' => ['name' => get_class($e), 'message' => $e->getMessage(), 'stack' => $e->getTraceAsString()]]);
} catch (\Throwable $e) {
    echo json_encode(['ok' => false, 'error' => ['name' => get_class($e), 'message' => $e->getMessage(), 'stack' => $e->getTraceAsString()]]);
}
} catch (\Throwable $e) {
    echo json_encode(['ok' => false, 'error' => ['name' => get_class($e), 'message' => $e->getMessage(), 'stack' => $e->getTraceAsString()]]);
}
`;

  const tmpFile = join(tmpdir(), `php-nonget-${Date.now()}-${randomUUID()}.php`);
  writeFileSync(tmpFile, phpCode);

  try {
    const child = spawnSync(
      "php",
      ["-d", "display_errors=0", "-d", "log_errors=1", "-d", "error_log=php://stderr", "-d", "html_errors=0", tmpFile],
      {
        input: JSON.stringify({ operationId, request, baseUrl, username, password }),
        encoding: "utf-8",
        cwd: join(__dirname, ".."),
        maxBuffer: 10 * 1024 * 1024,
      },
    );

    if (child.error) {
      return { ok: false, error: { name: "PHPSpawnError", message: child.error.message } };
    }

    const stdout = (child.stdout || "").trim();
    const stderr = (child.stderr || "").trim();
    if (stderr) console.error(`PHP stderr: ${stderr.split("\n").slice(0, 3).join(" ")}`);

    if (!stdout.startsWith("{") && !stdout.startsWith("[")) {
      return { ok: false, error: { name: "PHPRuntimeError", message: (stderr || stdout).slice(0, 500) } };
    }

    try {
      const parsed = JSON.parse(stdout);
      if (parsed?.ok) {
        let rawBody: any = null;
        if (typeof parsed.rawBody === "string" && parsed.rawBody) {
          try { rawBody = JSON.parse(parsed.rawBody); } catch { rawBody = parsed.rawBody; }
        }
        return { ok: true, value: parsed.value, statusCode: parsed.statusCode ?? null, rawBody };
      }
      return { ok: false, error: parsed?.error ?? { name: "PHPSDKError", message: stdout.slice(0, 500) } };
    } catch (e: any) {
      return { ok: false, error: { name: "PHPOutputParseError", message: `${e.message}: ${stdout.slice(0, 300)}` } };
    }
  } finally {
    try { if (existsSync(tmpFile)) unlinkSync(tmpFile); } catch { /* ignore */ }
  }
}

// A freshly-added track is fetched/processed asynchronously; generating
// subtitles before it exists returns 404 "track not found". Poll the media's
// track list until the track is present (and Ready when status is exposed).
async function waitForTrackReady(
  baseUrl: string,
  username: string,
  password: string,
  mediaId: string,
  trackId: string,
  timeoutMs = 180000,
  intervalMs = 5000,
): Promise<string> {
  const url = `${baseUrl.replace(/\/$/, "")}/on-demand/${mediaId}`;
  const deadline = Date.now() + timeoutMs;
  let last = "absent";
  while (Date.now() < deadline) {
    try {
      const res = await fetch(url, { headers: { Accept: "application/json", Authorization: basicAuthHeader(username, password) } });
      const body: any = await res.json().catch(() => null);
      const track = (body?.data?.tracks ?? []).find((t: any) => t?.id === trackId);
      if (track) {
        last = track.status ?? "present";
        if (last === "Ready" || last === "present") return last;
      }
    } catch {
      /* transient; keep polling */
    }
    await sleep(intervalMs);
  }
  return last;
}

// ---------------------------------------------------------------------------
// Spec + OpenAPI validation (shared with the GET validator)
// ---------------------------------------------------------------------------

function resolveSpecPath(): string {
  const candidates = [
    join(__dirname, "../fastpix.yaml"),
    join(__dirname, "../fixed.yaml"),
    join(__dirname, "../fastpix-openapi.yaml"),
    join(__dirname, "../../fastpix-openapi.yaml"),
  ];
  for (const p of candidates) if (existsSync(p)) return p;
  throw new Error(`OpenAPI spec not found. Tried: ${candidates.join(", ")}`);
}

function loadOpenAPISpec(): any {
  return yaml.load(readFileSync(resolveSpecPath(), "utf-8"));
}

function extractNonGetEndpoints(spec: any): Map<string, EndpointInfo> {
  const out = new Map<string, EndpointInfo>();
  for (const [path, methods] of Object.entries(spec.paths || {})) {
    const m = methods as any;
    for (const method of ["post", "put", "patch", "delete"]) {
      if (!m[method]) continue;
      out.set(m[method].operationId, {
        path,
        method: method.toUpperCase(),
        operationId: m[method].operationId,
        responses: m[method].responses || {},
      });
    }
  }
  return out;
}

function convertRefsToDefinitions(node: any): any {
  if (node == null || typeof node !== "object") return node;
  if (Array.isArray(node)) return node.map(convertRefsToDefinitions);
  const out: any = {};
  for (const [k, v] of Object.entries(node)) {
    if (k === "$ref" && typeof v === "string") out[k] = v.replace("#/components/schemas/", "#/definitions/");
    else out[k] = convertRefsToDefinitions(v);
  }
  return out;
}

function makeOpenAPIResponseValidator(spec: any, endpoint: EndpointInfo) {
  const definitions = convertRefsToDefinitions(spec.components?.schemas || {});
  const responses: any = {};
  for (const [status, def] of Object.entries(endpoint.responses || {})) {
    const d = def;
    const schema = d?.content?.["application/json"]?.schema;
    if (!schema) continue;
    responses[status] = { description: d.description || "", schema: convertRefsToDefinitions(schema) };
  }
  if (Object.keys(responses).length === 0) return null;
  return new OpenAPIResponseValidator({ responses, definitions });
}

// ---------------------------------------------------------------------------
// JSON diff helpers (shared with the GET validator)
// ---------------------------------------------------------------------------

// true for a value that contributes no meaningful path: an empty array,
// null/undefined, or an empty object. Used to prune entries when
// includeEmptyArrays is false.
function isEmptyish(v: any): boolean {
  if (Array.isArray(v)) return v.length === 0;
  if (v === null || v === undefined) return true;
  if (typeof v === "object") return Object.keys(v).length === 0;
  return false;
}

function collectArrayPaths(value: any[], prefix: string, opts: { includeEmptyArrays?: boolean }): Set<string> {
  const out = new Set<string>();
  const includeEmptyArrays = opts.includeEmptyArrays ?? true;
  if (!includeEmptyArrays && value.length === 0) return out;
  const arrayPrefix = prefix ? `${prefix}[]` : "[]";
  out.add(arrayPrefix);
  for (const item of value) for (const p of collectJsonPaths(item, arrayPrefix, opts)) out.add(p);
  return out;
}

function collectJsonPaths(value: any, prefix = "", opts: { includeEmptyArrays?: boolean } = {}): Set<string> {
  const out = new Set<string>();
  const includeEmptyArrays = opts.includeEmptyArrays ?? true;
  if (value === null || value === undefined) return out;
  if (typeof value !== "object") {
    if (prefix) out.add(prefix);
    return out;
  }
  if (Array.isArray(value)) {
    return collectArrayPaths(value, prefix, opts);
  }
  for (const [k, v] of Object.entries(value)) {
    if (!includeEmptyArrays && isEmptyish(v)) continue;
    const p = prefix ? `${prefix}.${k}` : k;
    out.add(p);
    for (const child of collectJsonPaths(v, p, opts)) out.add(child);
  }
  return out;
}

function canonicalizeKey(key: string): string {
  const camel = key.includes("_")
    ? key.toLowerCase().replace(/_([a-z0-9])/g, (_, c) => String(c).toUpperCase())
    : key;
  return camel.replaceAll("SDK", "Sdk").replaceAll("API", "Api");
}

function normalizeJsonForComparison(value: any): any {
  if (value === null || value === undefined) return value;
  if (Array.isArray(value)) return value.map(normalizeJsonForComparison);
  if (typeof value !== "object") return value;
  const out: any = {};
  for (const [k, v] of Object.entries(value)) out[canonicalizeKey(k)] = normalizeJsonForComparison(v);
  return out;
}

function sortUnique(arr: string[]) {
  return Array.from(new Set(arr)).sort((a, b) => a.localeCompare(b));
}

function jsonRoundTrip(value: any): any {
  return structuredClone(value);
}

// ---------------------------------------------------------------------------
// Lifecycle definition: ordered so all DELETEs run after every POST/PUT/PATCH.
// ---------------------------------------------------------------------------

const STEPS: Step[] = [
  // ---- CREATE ----
  { operationId: "create_signing_key", phase: "CREATE", request: () => ({}), capture: (v, c) => { c.signingKeyId = v?.data?.id; } },
  { operationId: "create-a-playlist", phase: "CREATE", request: () => ({}), capture: (v, c) => { c.playlistId = v?.data?.id; } },
  { operationId: "create-new-stream", phase: "CREATE", request: () => ({}), capture: (v, c) => { c.streamId = v?.data?.streamId ?? v?.data?.id; } },
  { operationId: "create-media", phase: "CREATE", request: () => ({}), capture: (v, c) => { c.mediaId = v?.data?.id; c.mediaPlaybackId = v?.data?.playbackIds?.[0]?.id; } },
  { operationId: "create-media-playback-id", phase: "CREATE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }), capture: (v, c) => { c.createdPlaybackId = v?.data?.playbackIds?.[0]?.id ?? v?.data?.id; } },
  { operationId: "Add-media-track", phase: "CREATE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }), capture: (v, c) => { c.trackId = v?.data?.id; } },
  { operationId: "create-playbackId-of-stream", phase: "CREATE", needs: ["streamId"], request: (c) => ({ streamId: c.streamId }), capture: (v, c) => { c.streamPlaybackId = v?.data?.playbackIds?.[0]?.id ?? v?.data?.id; } },
  { operationId: "create-simulcast-of-stream", phase: "CREATE", needs: ["streamId"], request: (c) => ({ streamId: c.streamId }), capture: (v, c) => { c.simulcastId = v?.data?.simulcastId ?? v?.data?.id; } },
  { operationId: "direct-upload-video-media", phase: "CREATE", request: () => ({}), capture: (v, c) => { c.uploadId = v?.data?.uploadId ?? v?.data?.id; } },
  { operationId: "Generate-subtitle-track", phase: "CREATE", needs: ["mediaId", "trackId"], request: (c) => ({ mediaId: c.mediaId, trackId: c.trackId }) },

  // ---- UPDATE (PUT/PATCH) ----
  { operationId: "updated-media", phase: "UPDATE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }) },
  { operationId: "updated-source-access", phase: "UPDATE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }) },
  { operationId: "updated-mp4Support", phase: "UPDATE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }) },
  { operationId: "update-media-summary", phase: "UPDATE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }) },
  { operationId: "update-media-chapters", phase: "UPDATE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }) },
  { operationId: "update-media-named-entities", phase: "UPDATE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }) },
  { operationId: "update-media-moderation", phase: "UPDATE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }) },
  { operationId: "update-media-track", phase: "UPDATE", needs: ["mediaId", "trackId"], request: (c) => ({ mediaId: c.mediaId, trackId: c.trackId }) },
  { operationId: "update-domain-restrictions", phase: "UPDATE", needs: ["mediaId", "mediaPlaybackId"], retryOn: "not ready for updates", request: (c) => ({ mediaId: c.mediaId, playbackId: c.mediaPlaybackId }) },
  { operationId: "update-user-agent-restrictions", phase: "UPDATE", needs: ["mediaId", "mediaPlaybackId"], retryOn: "not ready for updates", request: (c) => ({ mediaId: c.mediaId, playbackId: c.mediaPlaybackId }) },
  { operationId: "update-a-playlist", phase: "UPDATE", needs: ["playlistId"], request: (c) => ({ playlistId: c.playlistId }) },
  { operationId: "add-media-to-playlist", phase: "UPDATE", needs: ["playlistId", "mediaId"], request: (c) => ({ playlistId: c.playlistId, mediaId: c.mediaId }) },
  { operationId: "change-media-order-in-playlist", phase: "UPDATE", needs: ["playlistId", "mediaId"], request: (c) => ({ playlistId: c.playlistId, mediaId: c.mediaId }) },
  { operationId: "update-live-stream", phase: "UPDATE", needs: ["streamId"], request: (c) => ({ streamId: c.streamId }) },
  { operationId: "update-specific-simulcast-of-stream", phase: "UPDATE", needs: ["streamId", "simulcastId"], request: (c) => ({ streamId: c.streamId, simulcastId: c.simulcastId }) },
  // a freshly-created stream is already enabled, so disable first, then enable.
  { operationId: "disable-live-stream", phase: "UPDATE", needs: ["streamId"], request: (c) => ({ streamId: c.streamId }) },
  { operationId: "enable-live-stream", phase: "UPDATE", needs: ["streamId"], request: (c) => ({ streamId: c.streamId }) },
  // complete requires an actively-streaming encoder; with no ingest it is
  // expected to fail (the one allowed failure in a credentials-only run).
  { operationId: "complete-live-stream", phase: "UPDATE", needs: ["streamId"], request: (c) => ({ streamId: c.streamId }) },
  { operationId: "cancel-upload", phase: "UPDATE", needs: ["uploadId"], request: (c) => ({ uploadId: c.uploadId }) },

  // ---- DELETE (last) ----
  { operationId: "delete-media-from-playlist", phase: "DELETE", needs: ["playlistId", "mediaId"], request: (c) => ({ playlistId: c.playlistId, mediaId: c.mediaId }) },
  { operationId: "delete-a-playlist", phase: "DELETE", needs: ["playlistId"], request: (c) => ({ playlistId: c.playlistId }) },
  { operationId: "delete-media-track", phase: "DELETE", needs: ["mediaId", "trackId"], request: (c) => ({ mediaId: c.mediaId, trackId: c.trackId }) },
  { operationId: "delete-media-playback-id", phase: "DELETE", needs: ["mediaId", "createdPlaybackId"], request: (c) => ({ mediaId: c.mediaId, playbackId: c.createdPlaybackId }) },
  { operationId: "delete-simulcast-of-stream", phase: "DELETE", needs: ["streamId", "simulcastId"], request: (c) => ({ streamId: c.streamId, simulcastId: c.simulcastId }) },
  { operationId: "delete-playbackId-of-stream", phase: "DELETE", needs: ["streamId", "streamPlaybackId"], request: (c) => ({ streamId: c.streamId, playbackId: c.streamPlaybackId }) },
  { operationId: "delete-live-stream", phase: "DELETE", needs: ["streamId"], request: (c) => ({ streamId: c.streamId }) },
  { operationId: "delete-media", phase: "DELETE", needs: ["mediaId"], request: (c) => ({ mediaId: c.mediaId }) },
  { operationId: "delete_signing_key", phase: "DELETE", needs: ["signingKeyId"], request: (c) => ({ signingKeyId: c.signingKeyId }) },
];

// ---------------------------------------------------------------------------
// Artifacts + report
// ---------------------------------------------------------------------------

function writeArtifacts(operationId: string, rawBody: any, sdkValue: any) {
  const dir = join(__dirname, ARTIFACTS_DIRNAME);
  mkdirSync(dir, { recursive: true });
  const slug = safeFileSlug(operationId);
  writeFileSync(join(dir, `${slug}.raw.json`), toPrettyJson(rawBody ?? null));
  writeFileSync(join(dir, `${slug}.sdk.json`), toPrettyJson(sdkValue ?? null));
}

function openapiValidIcon(valid: boolean | null): string {
  if (valid === null) return "—";
  return valid ? "✅" : "❌";
}

function sdkIcon(r: StepResult): string {
  if (r.status === "SKIP") return "—";
  return r.sdkOk ? "✅" : "❌";
}

function statusIcon(status: StepResult["status"]): string {
  if (status === "PASS") return "✅ PASS";
  if (status === "SKIP") return "⤳ SKIP";
  return "❌ FAIL";
}

function missingCell(a: string[]): string {
  return a.length ? a.join(", ") : "None";
}

function buildConsolidatedRows(results: StepResult[]): string[] {
  const rows: string[] = [];
  const phaseOrder: Phase[] = ["CREATE", "UPDATE", "DELETE"];
  for (const phase of phaseOrder) {
    for (const r of results.filter((x) => x.phase === phase)) {
      rows.push(`| ${r.phase} | ${r.method} | \`${r.operationId}\` | ${r.httpStatus ?? "—"} | ${openapiValidIcon(r.openapiValid)} | ${sdkIcon(r)} | ${missingCell(r.missingInSDK)} | ${missingCell(r.missingInAPI)} | ${statusIcon(r.status)} |`);
    }
  }
  return rows;
}

function openapiErrorLines(r: StepResult): string[] {
  if (!r.openapiErrors.length) return [];
  const lines = ["- **OpenAPI errors**:"];
  for (const e of r.openapiErrors.slice(0, 20)) lines.push(`  - \`${e.path ?? ""}\` ${e.message ?? JSON.stringify(e)}`);
  return lines;
}

function missingLines(label: string, paths: string[]): string[] {
  if (!paths.length) return [];
  const lines = [`- **${label}**:`];
  for (const p of paths) lines.push(`  - \`${p}\``);
  return lines;
}

function perOpDetailLines(r: StepResult): string[] {
  const lines: string[] = [
    `### ${r.operationId} (\`${r.method} ${r.path}\`)`,
    `- **Phase**: ${r.phase}`,
    `- **Status**: ${r.status}`,
  ];
  if (r.httpStatus !== null) lines.push(`- **HTTP status**: ${r.httpStatus}`);
  if (r.capturedId) lines.push(`- **Captured id**: \`${r.capturedId}\``);
  if (r.note) lines.push(`- **Note**: ${r.note}`);
  if (r.sdkError) lines.push(`- **SDK error**: ${preview(r.sdkError)}`);
  lines.push(
    ...openapiErrorLines(r),
    ...missingLines("Missing in SDK (present in API)", r.missingInSDK),
    ...missingLines("Missing in API (present in SDK)", r.missingInAPI),
    "",
  );
  return lines;
}

function buildPerOpDetails(results: StepResult[]): string[] {
  return results.flatMap(perOpDetailLines);
}

function writeReport(results: StepResult[], ctx: Ctx) {
  const total = results.length;
  const pass = results.filter((r) => r.status === "PASS").length;
  const fail = results.filter((r) => r.status === "FAIL").length;
  const skip = results.filter((r) => r.status === "SKIP").length;

  const lines: string[] = [];
  lines.push(
    "# Non-GET endpoints validation report\n",
    `Generated: ${new Date().toISOString()}\n`,
    "## Summary\n",
    `- **Total**: ${total}`,
    `- **PASS**: ${pass}`,
    `- **FAIL**: ${fail}`,
    `- **SKIP**: ${skip}\n`,
    "## Captured resources\n",
  );
  for (const [k, v] of Object.entries(ctx)) lines.push(`- \`${k}\`: ${v ?? "(not created)"}`);
  lines.push(
    "",
    "## Consolidated report\n",
    "| Phase | Method | OperationId | HTTP | OpenAPI valid | SDK | Missing in SDK | Missing in API | Status |",
    "|---|---|---|---:|:--:|:--:|---|---|:--:|",
    ...buildConsolidatedRows(results),
    "",
    "## Per-operation details\n",
    ...buildPerOpDetails(results),
  );

  const reportPath = join(__dirname, REPORT_MD);
  writeFileSync(reportPath, lines.join("\n"));
  console.log(`Report generated: ${reportPath}`);
}

// ---------------------------------------------------------------------------
// Main
// ---------------------------------------------------------------------------

// maps a create step's operationId to the ctx field that holds its captured id
const CAPTURED_ID_FIELD: Partial<Record<string, keyof Ctx>> = {
  create_signing_key: "signingKeyId",
  "create-a-playlist": "playlistId",
  "create-new-stream": "streamId",
  "create-media": "mediaId",
  "create-media-playback-id": "createdPlaybackId",
  "Add-media-track": "trackId",
  "create-playbackId-of-stream": "streamPlaybackId",
  "create-simulcast-of-stream": "simulcastId",
  "direct-upload-video-media": "uploadId",
};

function resolveCapturedId(operationId: string, ctx: Ctx): string | undefined {
  const field = CAPTURED_ID_FIELD[operationId];
  return field ? ctx[field] || undefined : undefined;
}

async function callWithRetry(
  step: Step,
  request: Record<string, any>,
  baseUrl: string,
  username: string,
  password: string,
): Promise<PHPSDKResult> {
  let php = invokePHPSDK(step.operationId, request, baseUrl, username, password);
  if (!step.retryOn) return php;
  // wait for an async-provisioning resource (e.g. a playback id transitioning
  // from "preparing" to "available") by retrying while the error still matches.
  let attempt = 0;
  const maxAttempts = 24; // ~2 min at 5s
  while (!php.ok && attempt < maxAttempts && JSON.stringify(php.error ?? {}).includes(step.retryOn)) {
    attempt++;
    if (attempt === 1) process.stdout.write(`  ⏳ resource not ready, retrying`);
    else process.stdout.write(".");
    await sleep(5000);
    php = invokePHPSDK(step.operationId, request, baseUrl, username, password);
  }
  if (attempt > 0) console.log("");
  return php;
}

function validateOpenapi(
  spec: any,
  ep: EndpointInfo,
  statusCode: number | null,
  rawBody: any,
): { valid: boolean | null; errors: any[] } {
  const validator = makeOpenAPIResponseValidator(spec, ep);
  if (!validator || !statusCode) return { valid: null, errors: [] };
  const err = validator.validateResponse(String(statusCode), rawBody);
  return { valid: !err, errors: err?.errors ?? [] };
}

function computePathDiff(rawBody: any, value: any): { missingInSDK: string[]; missingInAPI: string[] } {
  const apiNorm = normalizeJsonForComparison(rawBody);
  const sdkNorm = value && typeof value === "object" ? normalizeJsonForComparison(jsonRoundTrip(value)) : null;
  const apiPaths = collectJsonPaths(apiNorm, "", { includeEmptyArrays: false });
  const sdkPaths = sdkNorm ? collectJsonPaths(sdkNorm, "", { includeEmptyArrays: false }) : new Set<string>();
  return {
    missingInSDK: sdkPaths.size ? sortUnique([...apiPaths].filter((p) => !sdkPaths.has(p))) : [],
    missingInAPI: sdkPaths.size ? sortUnique([...sdkPaths].filter((p) => !apiPaths.has(p))) : [],
  };
}

// Static configuration shared across every step of a run.
type RunConfig = {
  spec: any;
  endpoints: Map<string, EndpointInfo>;
  baseUrl: string;
  username: string;
  password: string;
};

async function processStep(
  step: Step,
  index: number,
  ctx: Ctx,
  cfg: RunConfig,
): Promise<StepResult> {
  const { spec, endpoints, baseUrl, username, password } = cfg;
  const ep = endpoints.get(step.operationId);
  const base = {
    operationId: step.operationId,
    method: ep?.method ?? "?",
    path: ep?.path ?? "?",
    phase: step.phase,
    openapiErrors: [] as any[],
    missingInSDK: [] as string[],
    missingInAPI: [] as string[],
  };

  console.log(`[${index + 1}/${STEPS.length}] (${step.phase}) ${step.operationId}`);

  if (!ep) {
    return { ...base, status: "SKIP", httpStatus: null, openapiValid: null, sdkOk: false, note: "operationId not found in spec" };
  }

  const missingDeps = (step.needs ?? []).filter((k) => !ctx[k]);
  if (missingDeps.length) {
    console.log(`  ⤳ SKIP (missing: ${missingDeps.join(", ")})`);
    return { ...base, status: "SKIP", httpStatus: null, openapiValid: null, sdkOk: false, note: `missing dependency: ${missingDeps.join(", ")}` };
  }

  // generating subtitles needs the just-added track to be fetched/ready first
  if (step.operationId === "Generate-subtitle-track" && ctx.mediaId && ctx.trackId) {
    process.stdout.write(`  ⏳ waiting for track ${ctx.trackId} to be ready...`);
    const tstatus = await waitForTrackReady(baseUrl, username, password, ctx.mediaId, ctx.trackId);
    console.log(` ${tstatus}`);
  }

  const request = step.request(ctx);
  const php = await callWithRetry(step, request, baseUrl, username, password);

  if (!php.ok) {
    const msg = `${php.error?.name ?? "Error"}: ${php.error?.message ?? "SDK call failed"}`;
    console.log(`  ❌ FAIL — ${msg.split("\n")[0].slice(0, 120)}`);
    writeArtifacts(step.operationId, php.error?.bodyJson ?? null, php.error ?? null);
    return { ...base, status: "FAIL", httpStatus: php.error?.statusCode ?? null, openapiValid: null, sdkOk: false, sdkError: msg };
  }

  // capture created ids for downstream steps
  if (step.capture) {
    step.capture(php.value, ctx);
  }

  // a just-created media must reach "Ready" before playback-ids / tracks can
  // be added, otherwise those create steps 400 and cascade into SKIPs.
  if (step.operationId === "create-media" && ctx.mediaId) {
    process.stdout.write(`  ⏳ waiting for media ${ctx.mediaId} to be Ready...`);
    const status = await waitForMediaReady(baseUrl, username, password, ctx.mediaId);
    console.log(` ${status}`);
  }
  // best-effort: surface whatever id this step just stored
  const capturedId = resolveCapturedId(step.operationId, ctx);

  // OpenAPI response-schema validation against the raw wire body
  const { valid: openapiValid, errors: openapiErrors } = validateOpenapi(spec, ep, php.statusCode, php.rawBody);

  // path diff between raw API body and SDK value
  const { missingInSDK, missingInAPI } = computePathDiff(php.rawBody, php.value);

  writeArtifacts(step.operationId, php.rawBody, php.value);

  const status: StepResult["status"] =
    (openapiValid === null || openapiValid) && missingInSDK.length === 0 && missingInAPI.length === 0
      ? "PASS"
      : "FAIL";

  const idSuffix = capturedId ? ` id=${capturedId}` : "";
  console.log(`  ${statusIcon(status)} (HTTP ${php.statusCode ?? "?"})${idSuffix}`);

  return {
    ...base,
    status,
    httpStatus: php.statusCode,
    openapiValid,
    openapiErrors,
    sdkOk: true,
    missingInSDK,
    missingInAPI,
    capturedId,
  };
}

async function main(): Promise<void> {
  const spec = loadOpenAPISpec();
  const endpoints = extractNonGetEndpoints(spec);

  const baseUrl: string =
    process.env.FASTPIX_BASE_URL
    ?? process.env.FASTPIX_SERVER_URL
    ?? ((spec.servers?.[0]?.url as string | undefined) ?? "https://api.fastpix.io/v1/");

  const username = process.env.FASTPIX_USERNAME ?? "";
  const password = process.env.FASTPIX_PASSWORD ?? "";
  if (!username || !password) {
    throw new Error("Set FASTPIX_USERNAME and FASTPIX_PASSWORD env vars for BasicAuth (use real credentials for live API validation)");
  }

  const ctx: Ctx = {};
  const results: StepResult[] = [];
  const cfg: RunConfig = { spec, endpoints, baseUrl, username, password };

  for (let i = 0; i < STEPS.length; i++) {
    results.push(await processStep(STEPS[i], i, ctx, cfg));
  }

  writeReport(results, ctx);

  const pass = results.filter((r) => r.status === "PASS").length;
  const fail = results.filter((r) => r.status === "FAIL").length;
  const skip = results.filter((r) => r.status === "SKIP").length;
  console.log(`Summary: total=${results.length} pass=${pass} fail=${fail} skip=${skip}`);
}

await main();
