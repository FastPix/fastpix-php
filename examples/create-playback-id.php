<?php

declare(strict_types=1);

// Create a playback ID for an existing READY media. The media must have finished
// processing. Run: php create-playback-id.php <mediaId>
require __DIR__ . '/../vendor/autoload.php';

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

$mediaId = $argv[1] ?? 'your-ready-media-id';

try {
    $response = $sdk->playback->createMediaPlaybackId(
        body: new Operations\CreateMediaPlaybackIdRequestBody(
            accessPolicy: Components\AccessPolicy::Public,
        ),
        mediaId: $mediaId,
    );
    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
}
