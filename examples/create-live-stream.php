<?php

declare(strict_types=1);

// Create a live stream. The response includes a streamKey — push RTMP to it with
// any encoder (OBS, ffmpeg). New streams start enabled. Run: php create-live-stream.php
require __DIR__ . '/../vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Errors;

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
    ),
);

try {
    $response = $sdk->startLiveStream->createNewStream(request: $request);
    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
}
