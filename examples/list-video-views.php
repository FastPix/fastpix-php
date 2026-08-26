<?php

declare(strict_types=1);

// List recent video views (analytics data). Run: php list-video-views.php
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

try {
    $response = $sdk->views->listVideoViews();
    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
}
