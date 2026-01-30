#!/usr/bin/env tsx
/*
 * GET endpoints validator using `openapi-response-validator`
 *
 * Per GET endpoint in OpenAPI spec:
 * - Calls the API to get the raw JSON response
 * - Validates the raw response against the OpenAPI response schema using `openapi-response-validator`
 * - Calls PHP SDK method for the same operationId, captures either the success object OR the thrown error (normalized)
 * - Compares JSON paths between raw API JSON and SDK-parsed JSON (including the same normalization rules)
 * - Persists per-endpoint artifacts to disk (API and SDK)
 * - Generates two markdown reports in `tests/`:
 *   - `GET_ENDPOINTS_OPENAPI_RESPONSE_VALIDATION_REPORT.md`
 *   - `GET_ENDPOINTS_OPENAPI_RESPONSE_FIX_SUGGESTIONS.md`
 * - Updates `tests/README.md` by replacing the block between markers:
 *   - `<!-- BEGIN GET_ENDPOINTS_CONSOLIDATED -->`
 *   - `<!-- END GET_ENDPOINTS_CONSOLIDATED -->`
 *
 * Requirements:
 * - FASTPIX_USERNAME / FASTPIX_PASSWORD env vars (Basic Auth)
 * - `tests/get-endpoints-fixtures.json` for endpoints with required path params (optional but recommended)
 */

/// <reference path="./shims.d.ts" />

import { readFileSync, writeFileSync, existsSync, mkdirSync, unlinkSync } from "node:fs";
import { tmpdir } from "node:os";
import { spawnSync } from "node:child_process";
import { join, dirname } from "node:path";
import { fileURLToPath } from "node:url";
import { createRequire } from "node:module";
import yaml from "js-yaml";

const require = createRequire(import.meta.url);
const openapiResponseValidatorMod = require("openapi-response-validator");
const OpenAPIResponseValidator =
  openapiResponseValidatorMod?.default ?? openapiResponseValidatorMod;

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

type Fixture = {
  operations: Record<
    string,
    {
      pathParams?: Record<string, string>;
      query?: Record<string, string | number | boolean | Array<string | number | boolean>>;
    }
  >;
};

type EndpointInfo = {
  path: string;
  method: "GET";
  operationId: string;
  responses: any;
  parameters: Array<any>;
};

type FixSuggestion = {
  title: string;
  why: string;
  where?: string;
  pasteYaml?: string;
};

type EndpointResult = {
  endpoint: string;
  operationId: string;
  method: "GET";
  openapiValid: boolean;
  openapiErrors: Array<{ path?: string; message?: string; errorCode?: string }>;
  sdkParseOk: boolean;
  sdkParseError?: string;
  missingInSDK: string[];
  missingInAPI: string[];
  emptyArraysOmittedInSDK: string[];
  emptyArraysOmittedInAPI: string[];
  apiResponseFile?: string;
  sdkResponseFile?: string;
  apiResponsePreview?: string;
  sdkResponsePreview?: string;
  status: "PASS" | "FAIL";
  note?: string;
  fixSuggestions?: FixSuggestion[];
};

const ARTIFACTS_DIRNAME = "artifacts";
const MAX_PREVIEW_CHARS = 4000;
const PLACEHOLDER_UUID = "00000000-0000-0000-0000-000000000000";
const FIX_SUGGESTIONS_MD = "GET_ENDPOINTS_OPENAPI_RESPONSE_FIX_SUGGESTIONS.md";

function safeFileSlug(input: string): string {
  return input.replace(/[^a-zA-Z0-9._-]+/g, "_");
}

function toPrettyJson(value: unknown): string {
  return JSON.stringify(value, null, 2);
}

function preview(text: string): string {
  if (text.length <= MAX_PREVIEW_CHARS) return text;
  return text.slice(0, MAX_PREVIEW_CHARS) + "\n... (truncated)";
}

function writeArtifactFiles(
  operationId: string,
  rawBody: unknown,
  sdkBody: unknown,
): {
  apiPath: string;
  sdkPath: string;
  apiPreview: string;
  sdkPreview: string;
} {
  const artifactsDir = join(__dirname, ARTIFACTS_DIRNAME);
  mkdirSync(artifactsDir, { recursive: true });

  const slug = safeFileSlug(operationId);
  const apiFilename = `${slug}.api.json`;
  const sdkFilename = `${slug}.sdk.json`;

  const apiText = toPrettyJson(rawBody);
  const sdkText = toPrettyJson(sdkBody);

  const apiPath = join(artifactsDir, apiFilename);
  const sdkPath = join(artifactsDir, sdkFilename);

  writeFileSync(apiPath, apiText);
  writeFileSync(sdkPath, sdkText);

  return {
    apiPath: `tests/${ARTIFACTS_DIRNAME}/${apiFilename}`,
    sdkPath: `tests/${ARTIFACTS_DIRNAME}/${sdkFilename}`,
    apiPreview: preview(apiText),
    sdkPreview: preview(sdkText),
  };
}

function defaultSDKRequest(operationId: string): any | undefined {
  // Ensure SDK input validation passes so we reach the HTTP call and get server errors on failures.
  switch (operationId) {
    case "get-media":
    case "get-media-summary":
    case "retrieveMediaInputInfo":
    case "list-playback-ids":
    case "get-media-clips":
      return { mediaId: PLACEHOLDER_UUID };
    case "get-playback-id":
      return { mediaId: PLACEHOLDER_UUID, playbackId: PLACEHOLDER_UUID };
    case "list-live-clips":
      return { livestreamId: PLACEHOLDER_UUID };
    case "get-playlist-by-id":
      return { playlistId: PLACEHOLDER_UUID };
    case "getDrmConfigurationById":
      return { drmConfigurationId: PLACEHOLDER_UUID };
    case "get-live-stream-by-id":
    case "get-live-stream-viewer-count-by-id":
      return { streamId: PLACEHOLDER_UUID };
    case "get-live-stream-playback-id":
      return { streamId: PLACEHOLDER_UUID, playbackId: PLACEHOLDER_UUID };
    case "get-specific-simulcast-of-stream":
      return { streamId: PLACEHOLDER_UUID, simulcastId: PLACEHOLDER_UUID };
    case "get-signing_key_by_id":
      return { signingKeyId: PLACEHOLDER_UUID };
    case "get_video_view_details":
      return { viewId: PLACEHOLDER_UUID };
    case "list_filter_values_for_dimension":
      return { dimensionsId: "browser_name" };
    case "list_breakdown_values":
      return {
        metricId: "quality_of_experience_score",
        timespan: "24:hours",
        groupBy: "browser_name",
      };
    case "list_overall_values":
      return { metricId: "quality_of_experience_score", timespan: "24:hours" };
    case "get_timeseries_data":
      return {
        metricId: "quality_of_experience_score",
        timespan: "24:hours",
        groupBy: "hour",
      };
    case "list_comparison_values":
      return { timespan: "24:hours", dimension: "browser_name", value: "Chrome" };
    case "list_errors":
      return { timespan: "24:hours", limit: 5 };
    case "list_video_views":
      return { timespan: "24:hours", limit: 5, offset: 1 };
    case "list_by_top_content":
      return { timespan: "24:hours", limit: 5 };
    case "list-media":
      return { limit: 5, offset: 1, orderBy: "desc" };
    case "list-uploads":
      return { limit: 5, offset: 1, orderBy: "desc" };
    case "get-all-streams":
      return { limit: 5, offset: 1, orderBy: "desc" };
    case "getDrmConfiguration":
      return { limit: 10, offset: 1 };
    case "get-all-playlists":
      return { limit: 5, offset: 1 };
    case "list_signing_keys":
      return { limit: 5, offset: 1 };
    case "list_dimensions":
      return undefined;
    default:
      return undefined;
  }
}

function buildSDKRequest(endpoint: EndpointInfo, fixtures: Fixture | null): any | undefined {
  const opFixture = fixtures?.operations?.[endpoint.operationId];
  const fromFixture = opFixture
    ? { ...(opFixture.pathParams || {}), ...(opFixture.query || {}) }
    : undefined;

  // If fixtures exist, use them as-is (they match SDK request shapes).
  if (fromFixture) return fromFixture;

  // Prefer operation-specific defaults (handles required query params too).
  const def = defaultSDKRequest(endpoint.operationId);
  if (def !== undefined) return def;

  // Otherwise: auto-generate a placeholder request object for required path params.
  const requiredPathParams = endpoint.parameters
    .filter((p) => p?.in === "path" && p?.required)
    .map((p) => p.name);

  if (requiredPathParams.length === 0) return undefined;

  const req: Record<string, string> = {};
  for (const name of requiredPathParams) req[name] = PLACEHOLDER_UUID;
  return req;
}

type PHPSDKResult =
  | { ok: true; value: any }
  | { ok: false; error: any };

function tryResolvePHPSdkSrc(): string {
  // When running from tests/, SDK src is ../src
  const candidates = [
    join(__dirname, "../src"),
  ];
  for (const p of candidates) {
    if (existsSync(p)) return p;
  }
  throw new Error(`Could not locate SDK src directory. Tried: ${candidates.map((c) => JSON.stringify(c)).join(", ")}`);
}

function invokePHPSDK(
  operationId: string,
  request: any,
  baseUrl: string,
  username: string,
  password: string,
): PHPSDKResult {
  const phpSdkSrc = tryResolvePHPSdkSrc();
  const vendorAutoload = join(__dirname, "../vendor/autoload.php");

  const phpCode = `<?php
declare(strict_types=1);

require_once ${JSON.stringify(vendorAutoload)};

use FastPix\\Sdk\\Fastpixsdk;
use FastPix\\Sdk\\Models\\Components;

// Suppress HTML error output and send errors to stderr
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');
error_reporting(E_ALL);

// Wrap everything in try-catch to prevent fatal errors from outputting HTML
try {

function to_jsonable($x) {
    // Handle null
    if ($x === null) {
        return null;
    }
    
    // Handle DateTime objects - convert to ISO string matching API format (must check before object check)
    if ($x instanceof \\DateTime || $x instanceof \\DateTimeInterface) {
        // Format to match API: "2026-01-27T11:45:25.434248Z"
        // Use 'Y-m-d\TH:i:s.u\Z' for microseconds with Z timezone
        $formatted = $x->format('Y-m-d\TH:i:s.u');
        $timezone = $x->getTimezone();
        if ($timezone->getName() === 'UTC' || $timezone->getName() === '+00:00' || $timezone->getOffset($x) === 0) {
            return $formatted . 'Z';
        }
        return $formatted . $timezone->format('P'); // Fallback to +00:00 format
    }
    
    // Handle enums (BackedEnum) - convert to their string/int value (must check before object check)
    if (is_object($x) && ($x instanceof \\BackedEnum)) {
        return $x->value;
    }
    
    // Handle arrays
    if (is_array($x)) {
        return array_map('to_jsonable', $x);
    }
    
    // Handle objects
    if (is_object($x)) {
        // Check for toArray method first
        if (method_exists($x, 'toArray')) {
            return to_jsonable($x->toArray());
        }
        
        // Convert object to array recursively using get_object_vars to properly access properties
        // This ensures we process each property value, converting enums and DateTime as we go
        $arr = [];
        $vars = get_object_vars($x);
        foreach ($vars as $k => $v) {
            // Recursively process each property value - this will convert nested enums and DateTime
            $arr[$k] = to_jsonable($v);
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
    
    // Check for statusCode property (APIException has public $statusCode)
    if (property_exists($e, 'statusCode')) {
        $out['statusCode'] = $e->statusCode;
    } elseif (method_exists($e, 'getStatusCode')) {
        $out['statusCode'] = $e->getStatusCode();
    }
    
    // Check for body property (APIException has public $body)
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
    
    if (method_exists($e, 'getPrevious') && $e->getPrevious() !== null) {
        $out['cause'] = $e->getPrevious()->getMessage();
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
        ->setSecurity(new Components\\Security(username: $username, password: $password));
    
    if ($base_url !== null && $base_url !== '') {
        $sdk = $sdk->setServerUrl($base_url);
    }
    
    $sdk = $sdk->build();
    
    $g = function($k) use ($req) { return $req[$k] ?? null; };
    
    $res = null;
    
    if ($op === 'list-media') {
        $res = $sdk->manageVideos->listMedia(limit: $g('limit'), offset: $g('offset'), orderBy: $g('orderBy') === 'desc' ? Components\\SortOrder::Desc : Components\\SortOrder::Asc);
    } elseif ($op === 'get-media') {
        $res = $sdk->manageVideos->getMedia(mediaId: $g('mediaId'));
    } elseif ($op === 'get-media-summary') {
        $res = $sdk->manageVideos->getMediaSummary(mediaId: $g('mediaId'));
    } elseif ($op === 'retrieveMediaInputInfo') {
        $res = $sdk->manageVideos->retrieveMediaInputInfo(mediaId: $g('mediaId'));
    } elseif ($op === 'list-uploads') {
        $res = $sdk->manageVideos->listUploads(limit: $g('limit'), offset: $g('offset'), orderBy: $g('orderBy') === 'desc' ? Components\\SortOrder::Desc : Components\\SortOrder::Asc);
    } elseif ($op === 'get-media-clips') {
        $res = $sdk->manageVideos->getMediaClips(mediaId: $g('mediaId'), offset: $g('offset'), limit: $g('limit'), orderBy: $g('orderBy') === 'desc' ? Components\\SortOrder::Desc : Components\\SortOrder::Asc);
    } elseif ($op === 'list-live-clips') {
        $res = $sdk->manageVideos->listLiveClips(livestreamId: $g('livestreamId'), limit: $g('limit'), offset: $g('offset'), orderBy: $g('orderBy') === 'desc' ? Components\\SortOrder::Desc : Components\\SortOrder::Asc);
    } elseif ($op === 'get-all-playlists') {
        $res = $sdk->playlist->getAllPlaylists(limit: $g('limit'), offset: $g('offset'));
    } elseif ($op === 'get-playlist-by-id') {
        $res = $sdk->playlist->getPlaylistById(playlistId: $g('playlistId'));
    } elseif ($op === 'list-playback-ids') {
        $res = $sdk->playback->listPlaybackIds(mediaId: $g('mediaId'));
    } elseif ($op === 'get-playback-id') {
        $res = $sdk->playback->getPlaybackId(mediaId: $g('mediaId'), playbackId: $g('playbackId'));
    } elseif ($op === 'getDrmConfiguration') {
        $res = $sdk->drmConfigurations->getDrmConfiguration(offset: $g('offset'), limit: $g('limit'));
    } elseif ($op === 'getDrmConfigurationById') {
        $res = $sdk->drmConfigurations->getDrmConfigurationById(drmConfigurationId: $g('drmConfigurationId'));
    } elseif ($op === 'get-all-streams') {
        $res = $sdk->manageLiveStream->getAllStreams(limit: $g('limit'), offset: $g('offset'), orderBy: $g('orderBy') === 'desc' ? \\FastPix\\Sdk\\Models\\Operations\\OrderBy::Desc : \\FastPix\\Sdk\\Models\\Operations\\OrderBy::Asc);
    } elseif ($op === 'get-live-stream-by-id') {
        $res = $sdk->manageLiveStream->getLiveStreamById(streamId: $g('streamId'));
    } elseif ($op === 'get-live-stream-viewer-count-by-id') {
        $res = $sdk->manageLiveStream->getLiveStreamViewerCountById(streamId: $g('streamId'));
    } elseif ($op === 'get-live-stream-playback-id') {
        $res = $sdk->livePlayback->getLiveStreamPlaybackId(streamId: $g('streamId'), playbackId: $g('playbackId'));
    } elseif ($op === 'get-specific-simulcast-of-stream') {
        $res = $sdk->simulcastStream->getSpecificSimulcastOfStream(streamId: $g('streamId'), simulcastId: $g('simulcastId'));
    } elseif ($op === 'list_signing_keys') {
        $res = $sdk->signingKeys->listSigningKeys(limit: $g('limit'), offset: $g('offset'));
    } elseif ($op === 'get-signing_key_by_id') {
        $res = $sdk->signingKeys->getSigningKeyById(signingKeyId: $g('signingKeyId'));
    } elseif ($op === 'list_video_views') {
        $timespanEnum = $g('timespan') ? \\FastPix\\Sdk\\Models\\Operations\\ListVideoViewsTimespan::tryFrom($g('timespan')) : null;
        $request = new \\FastPix\\Sdk\\Models\\Operations\\ListVideoViewsRequest(
            timespan: $timespanEnum,
            limit: $g('limit'),
            offset: $g('offset')
        );
        $res = $sdk->views->listVideoViews(request: $request);
    } elseif ($op === 'get_video_view_details') {
        $res = $sdk->views->getVideoViewDetails(viewId: $g('viewId'));
    } elseif ($op === 'list_by_top_content') {
        $timespanEnum = $g('timespan') ? \\FastPix\\Sdk\\Models\\Operations\\ListByTopContentTimespan::tryFrom($g('timespan')) : null;
        $res = $sdk->views->listByTopContent(timespan: $timespanEnum, limit: $g('limit'));
    } elseif ($op === 'list_dimensions') {
        $res = $sdk->dimensions->listDimensions();
    } elseif ($op === 'list_filter_values_for_dimension') {
        $dimensionsIdEnum = \\FastPix\\Sdk\\Models\\Operations\\DimensionsId::tryFrom($g('dimensionsId'));
        if ($dimensionsIdEnum === null) {
            throw new \\Exception('Invalid dimensionsId: ' . $g('dimensionsId'));
        }
        $timespanEnum = $g('timespan') ? \\FastPix\\Sdk\\Models\\Operations\\ListFilterValuesForDimensionTimespan::tryFrom($g('timespan')) : null;
        $res = $sdk->dimensions->listFilterValuesForDimension(
            dimensionsId: $dimensionsIdEnum,
            timespan: $timespanEnum
        );
    } elseif ($op === 'list_breakdown_values') {
        $metricIdEnum = \\FastPix\\Sdk\\Models\\Operations\\ListBreakdownValuesMetricId::tryFrom($g('metricId'));
        if ($metricIdEnum === null) {
            throw new \\Exception('Invalid metricId: ' . $g('metricId'));
        }
        $timespanEnum = \\FastPix\\Sdk\\Models\\Operations\\ListBreakdownValuesTimespan::tryFrom($g('timespan'));
        if ($timespanEnum === null) {
            throw new \\Exception('Invalid timespan: ' . $g('timespan'));
        }
        $request = new \\FastPix\\Sdk\\Models\\Operations\\ListBreakdownValuesRequest(
            metricId: $metricIdEnum,
            timespan: $timespanEnum,
            groupBy: $g('groupBy')
        );
        $res = $sdk->metrics->listBreakdownValues(request: $request);
    } elseif ($op === 'list_overall_values') {
        $metricIdEnum = \\FastPix\\Sdk\\Models\\Operations\\ListOverallValuesMetricId::tryFrom($g('metricId'));
        if ($metricIdEnum === null) {
            throw new \\Exception('Invalid metricId: ' . $g('metricId'));
        }
        $timespanEnum = \\FastPix\\Sdk\\Models\\Operations\\ListOverallValuesTimespan::tryFrom($g('timespan'));
        if ($timespanEnum === null) {
            throw new \\Exception('Invalid timespan: ' . $g('timespan'));
        }
        $res = $sdk->metrics->listOverallValues(
            metricId: $metricIdEnum,
            timespan: $timespanEnum
        );
    } elseif ($op === 'get_timeseries_data') {
        $metricIdEnum = \\FastPix\\Sdk\\Models\\Operations\\GetTimeseriesDataMetricId::tryFrom($g('metricId'));
        if ($metricIdEnum === null) {
            throw new \\Exception('Invalid metricId: ' . $g('metricId'));
        }
        $timespanEnum = \\FastPix\\Sdk\\Models\\Operations\\GetTimeseriesDataTimespan::tryFrom($g('timespan'));
        if ($timespanEnum === null) {
            throw new \\Exception('Invalid timespan: ' . $g('timespan'));
        }
        $groupByEnum = $g('groupBy') ? \\FastPix\\Sdk\\Models\\Operations\\GroupBy::tryFrom($g('groupBy')) : null;
        $request = new \\FastPix\\Sdk\\Models\\Operations\\GetTimeseriesDataRequest(
            metricId: $metricIdEnum,
            timespan: $timespanEnum,
            groupBy: $groupByEnum
        );
        $res = $sdk->metrics->getTimeseriesData(request: $request);
    } elseif ($op === 'list_comparison_values') {
        $timespanEnum = $g('timespan') ? \\FastPix\\Sdk\\Models\\Operations\\ListComparisonValuesTimespan::tryFrom($g('timespan')) : null;
        $dimensionEnum = $g('dimension') ? \\FastPix\\Sdk\\Models\\Operations\\Dimension::tryFrom($g('dimension')) : null;
        $res = $sdk->metrics->listComparisonValues(
            timespan: $timespanEnum,
            dimension: $dimensionEnum,
            value: $g('value')
        );
    } elseif ($op === 'list_errors') {
        $timespanEnum = $g('timespan') ? \\FastPix\\Sdk\\Models\\Operations\\ListErrorsTimespan::tryFrom($g('timespan')) : null;
        $res = $sdk->errors->listErrors(
            timespan: $timespanEnum,
            limit: $g('limit')
        );
    } else {
        echo json_encode(['ok' => false, 'error' => ['name' => 'SDKMappingError', 'message' => 'No PHP SDK method mapping for this operationId']]);
        exit(0);
    }
    
    // Extract the actual SDK response data from the response object
    // The SDK response objects have properties like 'object', 'array', 'string', 'mixed', etc.
    // or operation-specific properties. We want to extract the actual response data, not metadata.
    $actualResponse = null;
    
    // Metadata properties to skip
    $metadataProps = ['contentType', 'statusCode', 'rawResponse', 'defaultError', 'error'];
    
    // Try common response property names first
    $commonProps = ['object', 'array', 'string', 'mixed'];
    foreach ($commonProps as $prop) {
        if (property_exists($res, $prop) && $res->$prop !== null) {
            $actualResponse = $res->$prop;
            break;
        }
    }
    
    // If not found in common props, iterate through all properties using get_object_vars
    if ($actualResponse === null) {
        $vars = get_object_vars($res);
        foreach ($vars as $propName => $propValue) {
            // Skip metadata properties
            if (in_array($propName, $metadataProps)) {
                continue;
            }
            // Skip if property is null
            if ($propValue !== null) {
                $actualResponse = $propValue;
                break;
            }
        }
    }
    
    // If still no response found, use the entire response object (fallback)
    if ($actualResponse === null) {
        $actualResponse = $res;
    }
    
    // Convert to JSON-serializable format using to_jsonable
    // This normalizes enums to strings and DateTime to ISO strings
    $jsonable = to_jsonable($actualResponse);
    
    // Use json_encode with JSON_UNESCAPED_SLASHES for cleaner output
    echo json_encode(['ok' => true, 'value' => $jsonable], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} catch (\\Exception $e) {
    echo json_encode(['ok' => false, 'error' => normalize_err($e)], JSON_PRETTY_PRINT);
} catch (\\Error $e) {
    // Catch fatal errors (ParseError, TypeError, etc.)
    echo json_encode(['ok' => false, 'error' => [
        'name' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'stack' => $e->getTraceAsString()
    ]], JSON_PRETTY_PRINT);
} catch (\\Throwable $e) {
    // Catch any other throwable
    echo json_encode(['ok' => false, 'error' => [
        'name' => get_class($e),
        'message' => $e->getMessage(),
        'stack' => $e->getTraceAsString()
    ]], JSON_PRETTY_PRINT);
}
} catch (\\Throwable $e) {
    // Catch any errors during require or initialization
    echo json_encode(['ok' => false, 'error' => [
        'name' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'stack' => $e->getTraceAsString()
    ]], JSON_PRETTY_PRINT);
}
`;

  // Write PHP code to a temporary file and execute it
  // This avoids issues with PHP parse errors when using -r flag
  const tmpFile = join(tmpdir(), `php-sdk-${Date.now()}-${Math.random().toString(36).substring(7)}.php`);
  writeFileSync(tmpFile, phpCode);
  
  try {
    // Use -d flags to set ini values BEFORE parsing (parse errors happen before ini_set runs)
    // Also use -n to prevent loading php.ini files that might enable display_errors
    const child = spawnSync("php", [
      "-n", // No configuration (ini) file will be used
      "-d", "display_errors=0",
      "-d", "log_errors=1",
      "-d", "error_log=php://stderr",
      "-d", "html_errors=0", // Disable HTML formatting of errors
      tmpFile
    ], {
      input: JSON.stringify({ operationId, request, baseUrl, username, password }),
      encoding: "utf-8",
      cwd: join(__dirname, ".."),
      maxBuffer: 10 * 1024 * 1024,
    });

    if (child.error) {
      return { ok: false, error: { name: "PHPSpawnError", message: child.error.message } };
    }

    const stdout = (child.stdout || "").trim();
    const stderr = (child.stderr || "").trim();
    
    // Check stderr first - parse errors go to stderr even with display_errors=0
    if (stderr) {
      console.error(`PHP stderr: ${stderr}`);
      // Extract error message from stderr (remove HTML tags if present)
      const cleanError = stderr.replace(/<[^>]*>/g, '').trim() || stderr;
      // If stdout doesn't look like JSON, it's definitely an error
      if (!stdout.startsWith("{") && !stdout.startsWith("[")) {
        return { ok: false, error: { name: "PHPRuntimeError", message: cleanError } };
      }
      // Even if stdout has JSON, if stderr has parse errors, we should report them
      if (stderr.includes("Parse error") || stderr.includes("syntax error")) {
        return { ok: false, error: { name: "PHPSyntaxError", message: cleanError } };
      }
    }
    
    // Check if stdout contains HTML or non-JSON output (PHP error output)
    if (stdout.startsWith("<?") || stdout.startsWith("<") || (stdout.length > 0 && !stdout.startsWith("{") && !stdout.startsWith("["))) {
      // Parse errors might go to stdout in some PHP configurations
      const errorMsg = stderr || stdout.substring(0, 500);
      // Extract just the error message, not HTML tags
      const cleanError = errorMsg.replace(/<[^>]*>/g, '').trim() || errorMsg;
      return { ok: false, error: { name: "PHPSyntaxError", message: `PHP parse error: ${cleanError}` } };
    }

    try {
      const parsed = JSON.parse(stdout);
      if (parsed?.ok) return { ok: true, value: parsed.value };
      return { ok: false, error: parsed?.error ?? { name: "PHPSDKError", message: stdout } };
    } catch (e: any) {
      // If parsing fails, check if it's HTML output
      if (stdout.includes("<") || stdout.includes("<!DOCTYPE") || stdout.includes("<html")) {
        return { ok: false, error: { name: "PHPHTMLOutputError", message: "PHP output HTML instead of JSON. Check PHP syntax errors." } };
      }
      return { ok: false, error: { name: "PHPOutputParseError", message: `Failed to parse JSON: ${e.message}. Output: ${stdout.substring(0, 500)}` } };
    }
  } finally {
    // Clean up temp file
    try {
      if (existsSync(tmpFile)) {
        unlinkSync(tmpFile);
      }
    } catch (e) {
      // Ignore cleanup errors
    }
  }
}

function readFixtures(): Fixture | null {
  const p = join(__dirname, "get-endpoints-fixtures.json");
  if (!existsSync(p)) return null;
  return JSON.parse(readFileSync(p, "utf-8")) as Fixture;
}

function resolveSpecPath(): string {
  // Deterministic search order
  const candidates = [
    join(__dirname, "../fastpix.yaml"),
    join(__dirname, "../../fastpix.yaml"),
    join(__dirname, "../fixed.yaml"),
    join(__dirname, "../../fixed.yaml"),
    join(__dirname, "../fastpix-openapi.yaml"),
    join(__dirname, "../../fastpix-openapi.yaml"),
  ];
  for (const p of candidates) {
    if (existsSync(p)) return p;
  }
  throw new Error(
    `OpenAPI spec not found. Tried: ${candidates.map((c) => JSON.stringify(c)).join(", ")}`,
  );
}

function loadOpenAPISpec(): any {
  const specPath = resolveSpecPath();
  return yaml.load(readFileSync(specPath, "utf-8"));
}

function extractGetEndpoints(spec: any): EndpointInfo[] {
  const out: EndpointInfo[] = [];
  for (const [path, methods] of Object.entries(spec.paths || {})) {
    const m = methods as any;
    if (!m.get) continue;
    out.push({
      path,
      method: "GET",
      operationId: m.get.operationId,
      responses: m.get.responses || {},
      parameters: [...(m.get.parameters || []), ...(m.parameters || [])],
    });
  }
  return out;
}

// Convert OpenAPI 3 schema refs (#/components/schemas/X) to the format used by openapi-response-validator (#/definitions/X)
function convertRefsToDefinitions(node: any): any {
  if (node == null || typeof node !== "object") return node;
  if (Array.isArray(node)) return node.map(convertRefsToDefinitions);
  const out: any = {};
  for (const [k, v] of Object.entries(node)) {
    if (k === "$ref" && typeof v === "string") {
      out[k] = v.replace("#/components/schemas/", "#/definitions/");
    } else {
      out[k] = convertRefsToDefinitions(v);
    }
  }
  return out;
}

function makeOpenAPIResponseValidator(spec: any, endpoint: EndpointInfo) {
  const definitions = convertRefsToDefinitions(spec.components?.schemas || {});
  const responses: any = {};

  for (const [status, def] of Object.entries(endpoint.responses || {})) {
    const d = def as any;
    const schema = d?.content?.["application/json"]?.schema;
    if (!schema) continue;
    responses[status] = {
      description: d.description || "",
      schema: convertRefsToDefinitions(schema),
    };
  }

  if (Object.keys(responses).length === 0) return null;

  return new OpenAPIResponseValidator({
    responses,
    definitions,
  });
}

function hasOpenapiError(r: EndpointResult, includes: string): boolean {
  return (r.openapiErrors || []).some((e) => (e?.message ?? "").includes(includes));
}

function openapiErrorPaths(r: EndpointResult): string[] {
  return (r.openapiErrors || [])
    .map((e) => e?.path)
    .filter((p): p is string => typeof p === "string" && p.length > 0);
}

function generateFixSuggestions(r: EndpointResult): FixSuggestion[] {
  const out: FixSuggestion[] = [];
  const paths = openapiErrorPaths(r);

  // 1) Generic: oneOf overlap on tracks
  const hasTracksOneOf =
    hasOpenapiError(r, "must match exactly one schema in oneOf") &&
    paths.some((p) => p.includes("tracks"));
  if (hasTracksOneOf) {
    out.push({
      title: "Fix `tracks[].oneOf` overlap by constraining `type` per track schema",
      why:
        "The current track schemas overlap (e.g. `type` is a free string and distinguishing fields are not required), so a single track object can match multiple branches. `oneOf` requires exactly one match.",
      where:
        "In OpenAPI spec: `components/schemas/{VideoTrack,VideoTrackForGetAll,AudioTrack,SubtitleTrack}.properties.type`",
      pasteYaml: [
        "# Apply these changes inside each schema's `properties:` block:",
        "",
        "# VideoTrack (and VideoTrackForGetAll)",
        "type:",
        "  type: string",
        "  enum: [video]",
        "  example: video",
        "",
        "# AudioTrack",
        "type:",
        "  type: string",
        "  enum: [audio]",
        "  example: audio",
        "",
        "# SubtitleTrack",
        "type:",
        "  type: string",
        "  enum: [subtitle]",
        "  example: subtitle",
      ].join("\n"),
    });
  }

  // 2) Enum mismatch: sourceResolution
  const hasSourceResolutionEnum =
    hasOpenapiError(r, "must be equal to one of the allowed values") &&
    paths.some((p) => p.includes("sourceResolution"));
  if (hasSourceResolutionEnum) {
    out.push({
      title: "Fix `sourceResolution` enum mismatch (API may return values without `p`)",
      why:
        "The API can return values like `\"1080\"` but the spec constrains the enum to `\"1080p\"`-style values.",
      where:
        "In OpenAPI spec: under the relevant media response schema(s) `sourceResolution:` field definition",
    });
  }

  // 3) Redundant oneOf for /data/dimensions
  const hasDimensionsOneOf =
    hasOpenapiError(r, "must match exactly one schema in oneOf") &&
    (r.endpoint === "/data/dimensions" || paths.some((p) => p.includes("dimensions")));
  if (hasDimensionsOneOf) {
    out.push({
      title: "Remove redundant `oneOf` on `/data/dimensions` response schema",
      why:
        "`data` is defined as `oneOf: [array<string>, $ref: Dimensions]` and `Dimensions` itself is also `array<string>`, so valid responses can match multiple branches.",
      where:
        "In OpenAPI spec: `paths./data/dimensions.get.responses.200.content.application/json.schema.properties.data.oneOf`",
    });
  }

  // 4) Overlapping numeric oneOf: integer vs number
  const hasIntegerVsNumber =
    hasOpenapiError(r, "must match exactly one schema in oneOf") &&
    paths.some((p) => p.includes("value"));
  if (hasIntegerVsNumber) {
    out.push({
      title: "Avoid `oneOf: [integer, number]` overlaps (integers are also numbers)",
      why:
        "In JSON Schema, `integer` is a subset of `number`. A value like `0` matches both, causing oneOf validation errors.",
      where:
        "In OpenAPI spec: metrics schemas that use `oneOf: [integer, number]`",
    });
  }

  // 5) Nullable mismatch: fpApiVersion
  const hasFpApiVersionNull =
    hasOpenapiError(r, "must be string") &&
    paths.some((p) => p.includes("fpApiVersion"));
  if (hasFpApiVersionNull) {
    out.push({
      title: "Make `fpApiVersion` nullable in the spec",
      why: "The API can return `null` for fpApiVersion but the schema declares `string` only.",
      where: "In OpenAPI spec: `components/schemas/Views.properties.fpApiVersion`",
    });
  }

  // 6) Placeholder fixture guidance (common 404)
  const placeholderUsed = (r.note || "").includes("Placeholder used");
  const likely404 =
    r.sdkParseOk === false &&
    /404|not found/i.test(r.sdkParseError || "") &&
    placeholderUsed;
  if (likely404) {
    out.push({
      title: "Provide real fixture IDs for this operationId",
      why:
        "A placeholder UUID was used for required path params; the API likely returned 404 because the resource doesn't exist. Add a real ID under `tests/get-endpoints-fixtures.json` for this operationId.",
    });
  }

  // 7) Playlist playOrder default / missing
  const playOrderMissing = r.missingInAPI.some((p) => p.includes("playOrder")) ||
    r.missingInSDK.some((p) => p.includes("playOrder"));
  if (playOrderMissing) {
    out.push({
      title: "Ensure `playOrder` is correctly modeled for smart playlists only",
      why:
        "If `playOrder` is present/required only for `type: smart`, the response schemas should reflect that (e.g. discriminator split).",
      where:
        "In OpenAPI spec: playlist response schemas for create/update/get-by-id",
    });
  }

  // 8) simulcastResponses missing
  const hasSimulcastResponses = r.missingInSDK.some((p) => p.includes("simulcastResponses"));
  if (hasSimulcastResponses) {
    out.push({
      title: "Add `simulcastResponses` to the live stream response schema",
      why:
        "The API response includes simulcastResponses but the OpenAPI schema (and generated SDK inbound schema) does not, causing the SDK to drop the field.",
      where:
        "In OpenAPI spec: live stream response schema(s) for get/list streams",
    });
  }

  return out;
}

function writeFixSuggestions(results: EndpointResult[]) {
  const failing = results.filter((r) => r.status === "FAIL");
  const outPath = join(__dirname, FIX_SUGGESTIONS_MD);
  const lines: string[] = [];

  lines.push("# GET Endpoints — OpenAPI Response Fix Suggestions");
  lines.push("");
  lines.push(`Generated: ${new Date().toISOString()}`);
  lines.push("");
  lines.push(`Total failing endpoints: ${failing.length}`);
  lines.push("");

  for (const r of failing) {
    const suggestions = r.fixSuggestions ?? [];
    lines.push(`## ${r.operationId} (\`${r.endpoint}\`)`);
    lines.push("");
    lines.push(`- **Status**: ${r.status}`);
    lines.push(`- **OpenAPI valid**: ${r.openapiValid ? "yes" : "no"}`);
    lines.push(`- **SDK parse**: ${r.sdkParseOk ? "ok" : "failed"}`);
    if (r.apiResponseFile) lines.push(`- **API artifact**: \`${r.apiResponseFile}\``);
    if (r.sdkResponseFile) lines.push(`- **SDK artifact**: \`${r.sdkResponseFile}\``);
    lines.push("");

    if (!r.openapiValid && (r.openapiErrors?.length ?? 0) > 0) {
      lines.push("### Observed OpenAPI errors");
      lines.push("");
      for (const e of r.openapiErrors) {
        const loc = e.path ? `\`${e.path}\`` : "";
        const msg = e.message ?? "";
        lines.push(`- ${loc} ${msg}`.trim());
      }
      lines.push("");
    }

    if (suggestions.length === 0) {
      lines.push("### Suggested fixes");
      lines.push("");
      lines.push("- No heuristic suggestions available for this failure yet.");
      lines.push("");
      continue;
    }

    lines.push("### Suggested fixes");
    lines.push("");
    for (const s of suggestions) {
      lines.push(`- **${s.title}**`);
      lines.push(`  - **why**: ${s.why}`);
      if (s.where) lines.push(`  - **where**: ${s.where}`);
      if (s.pasteYaml) {
        lines.push("  - **paste**:");
        lines.push("");
        lines.push("```yaml");
        lines.push(s.pasteYaml);
        lines.push("```");
      }
      lines.push("");
    }
  }

  writeFileSync(outPath, lines.join("\n"));
}

function collectEmptyArrayFieldPaths(value: any, prefix = ""): Set<string> {
  const out = new Set<string>();
  if (value === null || value === undefined) return out;
  if (typeof value !== "object") return out;

  if (Array.isArray(value)) {
    const arrayPrefix = prefix ? `${prefix}[]` : "[]";
    for (const item of value) {
      for (const p of collectEmptyArrayFieldPaths(item, arrayPrefix)) out.add(p);
    }
    return out;
  }

  for (const [k, v] of Object.entries(value)) {
    const p = prefix ? `${prefix}.${k}` : k;
    if (Array.isArray(v) && v.length === 0) out.add(p);
    for (const child of collectEmptyArrayFieldPaths(v, p)) out.add(child);
  }
  return out;
}

function collectJsonPaths(
  value: any,
  prefix = "",
  opts: { includeEmptyArrays?: boolean } = {},
): Set<string> {
  const out = new Set<string>();
  const add = (p: string) => out.add(p);
  const includeEmptyArrays = opts.includeEmptyArrays ?? true;

  if (value === null || value === undefined) return out;
  if (typeof value !== "object") {
    if (prefix) add(prefix);
    return out;
  }

  if (Array.isArray(value)) {
    if (!includeEmptyArrays && value.length === 0) return out;
    const arrayPrefix = prefix ? `${prefix}[]` : "[]";
    add(arrayPrefix);
    for (const item of value) {
      for (const p of collectJsonPaths(item, arrayPrefix, opts)) out.add(p);
    }
    return out;
  }

  for (const [k, v] of Object.entries(value)) {
    if (!includeEmptyArrays && Array.isArray(v) && v.length === 0) {
      continue;
    }
    const p = prefix ? `${prefix}.${k}` : k;
    add(p);
    for (const child of collectJsonPaths(v, p, opts)) out.add(child);
  }
  return out;
}

function sortUnique(arr: string[]) {
  return Array.from(new Set(arr)).sort((a, b) => a.localeCompare(b));
}

function canonicalizeKey(key: string): string {
  // 1) snake_case -> camelCase
  const camel = key.includes("_")
    ? key
        .toLowerCase()
        .replace(/_([a-z0-9])/g, (_, c) => String(c).toUpperCase())
    : key;

  // 2) normalize acronyms casing
  return camel.replaceAll("SDK", "Sdk").replaceAll("API", "Api");
}

function normalizeJsonForComparison(value: any): any {
  if (value === null || value === undefined) return value;
  if (Array.isArray(value)) return value.map(normalizeJsonForComparison);
  if (typeof value !== "object") return value;
  const out: any = {};
  for (const [k, v] of Object.entries(value)) {
    out[canonicalizeKey(k)] = normalizeJsonForComparison(v);
  }
  return out;
}

function jsonRoundTrip(value: any): any {
  return JSON.parse(JSON.stringify(value));
}

function buildUrl(
  baseUrl: string,
  endpoint: EndpointInfo,
  fixture: Fixture | null,
): { url: string; note?: string } {
  const opFixture = fixture?.operations?.[endpoint.operationId];
  let path = endpoint.path;

  const requiredPathParams = endpoint.parameters
    .filter((p) => p?.in === "path" && p?.required)
    .map((p) => p.name);

  const defaults = defaultSDKRequest(endpoint.operationId) ?? {};
  const fromFixture = opFixture
    ? { ...(opFixture.pathParams || {}), ...(opFixture.query || {}) }
    : {};
  const effectiveReq: Record<string, any> = { ...defaults, ...fromFixture };

  let note: string | undefined;
  if (requiredPathParams.length > 0) {
    for (const name of requiredPathParams) {
      const val = effectiveReq[name] ?? PLACEHOLDER_UUID;
      if (effectiveReq[name] == null) {
        note = note ? `${note}; placeholder used for ${name}` : `Placeholder used for ${name}`;
      }
      path = path.replaceAll(`{${name}}`, encodeURIComponent(val));
    }
  }

  const base = baseUrl.endsWith("/") ? baseUrl : baseUrl + "/";
  const url = new URL(path.replace(/^\//, ""), base);

  const queryParams = endpoint.parameters.filter((p) => p?.in === "query");
  for (const p of queryParams) {
    const name: string = p.name;
    const baseName = name.endsWith("[]") ? name.slice(0, -2) : name;
    const val = effectiveReq[name] ?? effectiveReq[baseName];
    if (val == null) continue;

    if (Array.isArray(val)) {
      for (const item of val) url.searchParams.append(name, String(item));
    } else {
      if (name.endsWith("[]")) url.searchParams.append(name, String(val));
      else url.searchParams.set(name, String(val));
    }
  }

  return { url: url.toString(), note };
}

function basicAuthHeader(username: string, password: string): string {
  const token = Buffer.from(`${username}:${password}`).toString("base64");
  return `Basic ${token}`;
}

function writeReport(results: EndpointResult[]) {
  const total = results.length;
  const passed = results.filter((r) => r.status === "PASS").length;
  const failed = results.filter((r) => r.status === "FAIL").length;
  const skipped = 0;

  const reportPath = join(__dirname, "GET_ENDPOINTS_OPENAPI_RESPONSE_VALIDATION_REPORT.md");
  const readmePath = join(__dirname, "README.md");
  const generatedAt = new Date().toISOString();

  const lines: string[] = [];
  lines.push("# GET Endpoints — OpenAPI Response Validation Report");
  lines.push("");
  lines.push(`Generated: ${generatedAt}`);
  lines.push("");
  lines.push("## Summary");
  lines.push("");
  lines.push(`- **Total GET endpoints**: ${total}`);
  lines.push(`- **PASS**: ${passed}`);
  lines.push(`- **FAIL**: ${failed}`);
  lines.push(`- **SKIP**: ${skipped}`);
  lines.push("");
  lines.push("## Consolidated report");
  lines.push("");
  lines.push("| Endpoint | OperationId | OpenAPI valid | SDK parse | Missing in SDK (present in API) | Missing in API (present in SDK) | Empty arrays omitted by SDK | Status |");
  lines.push("|---|---|---:|---:|---|---|---|---|");

  for (const r of results) {
    const openapiCol = r.openapiValid ? "✅" : "❌";
    const sdkCol = r.sdkParseOk ? "✅" : "❌";
    const missSdk = r.missingInSDK.length ? r.missingInSDK.map((p) => `\`${p}\``).join(", ") : "None";
    const missApi = r.missingInAPI.length ? r.missingInAPI.map((p) => `\`${p}\``).join(", ") : "None";
    const emptyOmitted = r.emptyArraysOmittedInSDK.length ? r.emptyArraysOmittedInSDK.map((p) => `\`${p}\``).join(", ") : "None";
    const status = r.status === "PASS" ? "✅ PASS" : "❌ FAIL";
    lines.push(`| \`${r.endpoint}\` | \`${r.operationId}\` | ${openapiCol} | ${sdkCol} | ${missSdk} | ${missApi} | ${emptyOmitted} | ${status} |`);
  }

  lines.push("");
  lines.push("## Per-endpoint details (full missing parameter lists)");
  lines.push("");

  for (const r of results) {
    lines.push(`### ${r.operationId} (\`${r.endpoint}\`)`);
    lines.push("");
    lines.push(`- **Status**: ${r.status}`);
    if (r.note) lines.push(`- **Note**: ${r.note}`);
    lines.push(`- **OpenAPI valid**: ${r.openapiValid ? "yes" : "no"}`);
    if (!r.openapiValid && r.openapiErrors.length) {
      lines.push("- **OpenAPI errors**:");
      for (const e of r.openapiErrors) {
        const loc = e.path ? `\`${e.path}\`` : "";
        const msg = e.message ?? "";
        lines.push(`  - ${loc} ${msg}`.trim());
      }
    }
    lines.push(`- **SDK parse**: ${r.sdkParseOk ? "ok" : "failed"}`);
    if (!r.sdkParseOk && r.sdkParseError) lines.push(`- **SDK parse error**: ${r.sdkParseError}`);
    if (r.apiResponseFile) lines.push(`- **API response file**: \`${r.apiResponseFile}\``);
    if (r.sdkResponseFile) lines.push(`- **SDK response file**: \`${r.sdkResponseFile}\``);
    lines.push("");

    if (r.apiResponsePreview) {
      lines.push("**API response (preview)**");
      lines.push("");
      lines.push("```json");
      lines.push(r.apiResponsePreview);
      lines.push("```");
      lines.push("");
    }

    if (r.sdkResponsePreview) {
      lines.push("**SDK response (preview)**");
      lines.push("");
      lines.push("```json");
      lines.push(r.sdkResponsePreview);
      lines.push("```");
      lines.push("");
    }

    lines.push(`**Missing in SDK (present in API) — ${r.missingInSDK.length}**`);
    lines.push("");
    if (r.missingInSDK.length === 0) lines.push("- None");
    else for (const p of r.missingInSDK) lines.push(`- \`${p}\``);
    lines.push("");

    lines.push(`**Missing in API (present in SDK) — ${r.missingInAPI.length}**`);
    lines.push("");
    if (r.missingInAPI.length === 0) lines.push("- None");
    else for (const p of r.missingInAPI) lines.push(`- \`${p}\``);
    lines.push("");

    lines.push(`**Empty arrays omitted by SDK — ${r.emptyArraysOmittedInSDK.length}**`);
    lines.push("");
    if (r.emptyArraysOmittedInSDK.length === 0) lines.push("- None");
    else for (const p of r.emptyArraysOmittedInSDK) lines.push(`- \`${p}\``);
    lines.push("");

    lines.push(`**Empty arrays omitted by API — ${r.emptyArraysOmittedInAPI.length}**`);
    lines.push("");
    if (r.emptyArraysOmittedInAPI.length === 0) lines.push("- None");
    else for (const p of r.emptyArraysOmittedInAPI) lines.push(`- \`${p}\``);
    lines.push("");
  }

  writeFileSync(reportPath, lines.join("\n"));
  writeFixSuggestions(results);

  // Also update tests/README.md with the consolidated report section so it always stays in sync.
  try {
    if (existsSync(readmePath)) {
      const begin = "<!-- BEGIN GET_ENDPOINTS_CONSOLIDATED -->";
      const end = "<!-- END GET_ENDPOINTS_CONSOLIDATED -->";

      const consolidated: string[] = [];
      consolidated.push(`Last generated: ${generatedAt}`);
      consolidated.push("");
      consolidated.push(`- **Total GET endpoints**: ${total}`);
      consolidated.push(`- **PASS**: ${passed}`);
      consolidated.push(`- **FAIL**: ${failed}`);
      consolidated.push(`- **SKIP**: ${skipped}`);
      consolidated.push("");
      consolidated.push("| Endpoint | OperationId | OpenAPI valid | SDK parse | Missing in SDK (present in API) | Missing in API (present in SDK) | Empty arrays omitted by SDK | Status |");
      consolidated.push("|---|---|---:|---:|---|---|---|---|");
      for (const r of results) {
        const openapiCol = r.openapiValid ? "✅" : "❌";
        const sdkCol = r.sdkParseOk ? "✅" : "❌";
        const missSdk = r.missingInSDK.length ? r.missingInSDK.map((p) => `\`${p}\``).join(", ") : "None";
        const missApi = r.missingInAPI.length ? r.missingInAPI.map((p) => `\`${p}\``).join(", ") : "None";
        const emptyOmitted = r.emptyArraysOmittedInSDK.length ? r.emptyArraysOmittedInSDK.map((p) => `\`${p}\``).join(", ") : "None";
        const status = r.status === "PASS" ? "✅ PASS" : "❌ FAIL";
        consolidated.push(`| \`${r.endpoint}\` | \`${r.operationId}\` | ${openapiCol} | ${sdkCol} | ${missSdk} | ${missApi} | ${emptyOmitted} | ${status} |`);
      }
      consolidated.push("");
      consolidated.push("#### Missing fields (full lists)");
      consolidated.push("");
      for (const r of results) {
        consolidated.push(`- **${r.operationId}** (\`${r.endpoint}\`)`);
        consolidated.push(`  - **Missing in SDK (present in API)**: ${r.missingInSDK.length ? r.missingInSDK.map((p) => `\`${p}\``).join(", ") : "None"}`);
        consolidated.push(`  - **Missing in API (present in SDK)**: ${r.missingInAPI.length ? r.missingInAPI.map((p) => `\`${p}\``).join(", ") : "None"}`);
        consolidated.push(`  - **Empty arrays omitted by SDK**: ${r.emptyArraysOmittedInSDK.length ? r.emptyArraysOmittedInSDK.map((p) => `\`${p}\``).join(", ") : "None"}`);
        consolidated.push(`  - **Empty arrays omitted by API**: ${r.emptyArraysOmittedInAPI.length ? r.emptyArraysOmittedInAPI.map((p) => `\`${p}\``).join(", ") : "None"}`);
      }
      consolidated.push("");
      consolidated.push(`Full details: \`tests/GET_ENDPOINTS_OPENAPI_RESPONSE_VALIDATION_REPORT.md\``);

      const readme = readFileSync(readmePath, "utf-8");
      if (readme.includes(begin) && readme.includes(end)) {
        const block = `${begin}\n${consolidated.join("\n")}\n${end}`;
        const updated = readme.replace(new RegExp(`${begin}[\\s\\S]*?${end}`), block);
        writeFileSync(readmePath, updated);
      }
    }
  } catch {
    // ignore README update failures
  }

  // eslint-disable-next-line no-console
  console.log(`Report generated: ${reportPath}`);
  // eslint-disable-next-line no-console
  console.log(`Fix suggestions generated: ${join(__dirname, FIX_SUGGESTIONS_MD)}`);
  // eslint-disable-next-line no-console
  console.log(`Summary: total=${total} pass=${passed} fail=${failed} skip=${skipped}`);
}

async function main(): Promise<void> {
  const spec = loadOpenAPISpec();
  const endpoints = extractGetEndpoints(spec);
  const fixtures = readFixtures();

  const baseUrl: string =
    process.env.FASTPIX_BASE_URL
    ?? ((spec.servers?.[0]?.url as string | undefined) ?? "https://api.fastpix.io/v1/");

  const username = process.env.FASTPIX_USERNAME ?? "your-access-token";
  const password = process.env.FASTPIX_PASSWORD ?? "your-secret-key";

  if (!username || !password || username === "your-access-token" || password === "your-secret-key") {
    throw new Error("Set FASTPIX_USERNAME and FASTPIX_PASSWORD env vars for BasicAuth (use real credentials for live API validation)");
  }

  const results: EndpointResult[] = [];
  const totalEndpoints = endpoints.length;

  for (let i = 0; i < endpoints.length; i++) {
    const ep = endpoints[i];
    // eslint-disable-next-line no-console
    console.log(`[${i + 1}/${totalEndpoints}] Processing: ${ep.operationId} (${ep.path})`);

    try {
      const { url, note } = buildUrl(baseUrl, ep, fixtures);

      let httpStatus = 0;
      let rawBody: any = null;
      let requestError: string | undefined;
      try {
        // Add timeout to prevent hanging
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout

        const res = await fetch(url, {
          method: "GET",
          headers: {
            Accept: "application/json",
            Authorization: basicAuthHeader(username, password),
          },
          signal: controller.signal,
        });

        clearTimeout(timeoutId);

        httpStatus = res.status;
        const bodyText = await res.text();
        try {
          rawBody = bodyText ? JSON.parse(bodyText) : null;
        } catch {
          rawBody = bodyText;
        }
      } catch (e: any) {
        if (e.name === 'AbortError') {
          requestError = "Request timeout (30s)";
        } else {
          requestError = e?.message ?? String(e);
        }
        // eslint-disable-next-line no-console
        console.error(`  ⚠️  API request failed: ${requestError}`);
      }

      const validator = makeOpenAPIResponseValidator(spec, ep);
      let openapiValid = true;
      let openapiErrors: any[] = [];
      if (requestError) {
        openapiValid = false;
        openapiErrors = [{ message: `Request failed: ${requestError}` }];
      } else if (validator) {
        const err = validator.validateResponse(String(httpStatus), rawBody);
        if (err) {
          openapiValid = false;
          openapiErrors = err.errors || [];
        }
      }

      // SDK output: call SDK and capture success result or thrown error (normalized).
      const sdkReq = buildSDKRequest(ep, fixtures);
      let sdkParseOk = true;
      let sdkParseError: string | undefined;
      let sdkPrinted: any = null;
      let sdkValueForDiff: any = null;

      const php = invokePHPSDK(ep.operationId, sdkReq, baseUrl, username, password);
      if (php.ok) {
        sdkValueForDiff = php.value;
        sdkPrinted = php.value;
      } else {
        sdkParseOk = false;
        sdkParseError = php.error?.message ?? "PHP SDK call failed";
        sdkPrinted = php.error;
        // eslint-disable-next-line no-console
        console.error(`  ⚠️  PHP SDK call failed: ${sdkParseError}`);
      }

      const apiNormalized = normalizeJsonForComparison(rawBody);
      const sdkJsonLike =
        (sdkValueForDiff && typeof sdkValueForDiff === "object")
          ? jsonRoundTrip(sdkValueForDiff)
          : null;
      const sdkNormalized = sdkJsonLike ? normalizeJsonForComparison(sdkJsonLike) : null;

      // Treat `[]` the same as "missing" for comparison.
      const apiPaths = collectJsonPaths(apiNormalized, "", { includeEmptyArrays: false });
      const sdkPaths = sdkNormalized ? collectJsonPaths(sdkNormalized, "", { includeEmptyArrays: false }) : new Set<string>();

      const missingInSDK = sdkPaths.size
        ? sortUnique([...apiPaths].filter((p) => !sdkPaths.has(p)))
        : [];
      const missingInAPI = sdkPaths.size
        ? sortUnique([...sdkPaths].filter((p) => !apiPaths.has(p)))
        : [];

      const apiStrictPaths = collectJsonPaths(apiNormalized, "", { includeEmptyArrays: true });
      const sdkStrictPaths = sdkNormalized ? collectJsonPaths(sdkNormalized, "", { includeEmptyArrays: true }) : new Set<string>();
      const apiEmptyArrayFields = collectEmptyArrayFieldPaths(apiNormalized);
      const sdkEmptyArrayFields = sdkNormalized ? collectEmptyArrayFieldPaths(sdkNormalized) : new Set<string>();

      const emptyArraysOmittedInSDK = sortUnique([...apiEmptyArrayFields].filter((p) => !sdkStrictPaths.has(p)));
      const emptyArraysOmittedInAPI = sortUnique([...sdkEmptyArrayFields].filter((p) => !apiStrictPaths.has(p)));

      const pass = openapiValid && sdkParseOk && missingInSDK.length === 0 && missingInAPI.length === 0;

      const artifacts = writeArtifactFiles(
        ep.operationId,
        rawBody,
        sdkPrinted,
      );

      results.push({
        endpoint: ep.path,
        operationId: ep.operationId,
        method: "GET",
        openapiValid,
        openapiErrors,
        sdkParseOk,
        sdkParseError,
        missingInSDK,
        missingInAPI,
        emptyArraysOmittedInSDK,
        emptyArraysOmittedInAPI,
        apiResponseFile: artifacts.apiPath,
        sdkResponseFile: artifacts.sdkPath,
        apiResponsePreview: artifacts.apiPreview,
        sdkResponsePreview: artifacts.sdkPreview,
        status: pass ? "PASS" : "FAIL",
        note,
        fixSuggestions: undefined,
      });

      // eslint-disable-next-line no-console
      console.log(`  ✓ Completed: ${ep.operationId} - ${results[results.length - 1].status}`);
    } catch (error: any) {
      // Catch any unexpected errors and continue with next endpoint
      // eslint-disable-next-line no-console
      console.error(`  ✗ Unexpected error processing ${ep.operationId}:`, error?.message ?? String(error));
      results.push({
        endpoint: ep.path,
        operationId: ep.operationId,
        method: "GET",
        openapiValid: false,
        openapiErrors: [{ message: `Unexpected error: ${error?.message ?? String(error)}` }],
        sdkParseOk: false,
        sdkParseError: error?.message ?? String(error),
        missingInSDK: [],
        missingInAPI: [],
        emptyArraysOmittedInSDK: [],
        emptyArraysOmittedInAPI: [],
        status: "FAIL",
        note: "Unexpected error during processing",
        fixSuggestions: undefined,
      });
    }
  }

  for (const r of results) {
    if (r.status !== "FAIL") continue;
    r.fixSuggestions = generateFixSuggestions(r);
  }

  writeReport(results);
}

await main();
