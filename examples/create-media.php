<?php

declare(strict_types=1);

// Create media from a publicly accessible URL. FastPix fetches and processes it,
// then sends the video.media.ready webhook when it's done. Run: php create-media.php
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

$request = new Components\CreateMediaRequest(
    inputs: [
        new Components\PullVideoInput(
            url: 'https://static.fastpix.io/sample.mp4',
        ),
    ],
    metadata: ['key1' => 'value1'],
);

try {
    $response = $sdk->inputVideo->createMedia(request: $request);
    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
}
