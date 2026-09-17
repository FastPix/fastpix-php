<?php

declare(strict_types=1);

// Create a live stream, then add a playback ID restricted to example.com and
// tighten its domain policy. The response includes a streamKey — push RTMP to it
// with any encoder (OBS, ffmpeg). Run: php create-live-stream.php
require_once __DIR__.'/../vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Errors;
use FastPix\Sdk\Models\Operations;

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(new Components\Security(
        username: getenv('FASTPIX_USERNAME') ?: '',
        password: getenv('FASTPIX_PASSWORD') ?: '',
    ))
    ->build();

$request = new Components\CreateLiveStreamRequest(
    playbackSettings: new Components\PlaybackSettings(
        accessPolicy: Components\BasicAccessPolicy::Public,
    ),
    inputMediaSettings: new Components\InputMediaSettings(
        metadata: ['key1' => 'value1'],
        reconnectWindow: 60,
        enableRecording: true, // set false to skip the Live-to-VOD recording
    ),
);

try {
    $response = $sdk->startLiveStream->createNewStream(request: $request);
    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n";
    $streamId = $response->liveStreamResponseDTO?->data?->streamId ?? '';

    // Add a playback ID that only example.com may embed.
    $playback = $sdk->livePlayback->createPlaybackIdOfStream(
        body: new Components\PlaybackIdRequest(
            accessPolicy: Components\BasicAccessPolicy::Public,
            accessRestrictions: new Components\PlaybackIdAccessRestrictions(
                domains: new Components\PlaybackIdDomains(
                    defaultPolicy: Components\PolicyAction::Deny,
                    allow: ['example.com'],
                ),
            ),
        ),
        streamId: $streamId,
    );
    $playbackId = $playback->playbackIdSuccessResponse?->data?->id ?? '';

    // Later, change the domain policy without recreating the playback ID.
    $restrictions = $sdk->livePlayback->updateDomainRestrictions(
        body: new Operations\UpdateLiveStreamDomainRestrictionsRequestBody(
            allow: ['example.com', 'www.example.com'],
            defaultPolicy: Operations\UpdateLiveStreamDomainRestrictionsDefaultPolicy::Deny,
        ),
        streamId: $streamId,
        playbackId: $playbackId,
    );
    echo json_encode(json_decode((string) $restrictions->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
}
