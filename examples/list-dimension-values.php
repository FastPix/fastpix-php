<?php

declare(strict_types=1);

// List the values seen for a metrics dimension (e.g. browsers). Run: php list-dimension-values.php
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

try {
    $response = $sdk->dimensions->listFilterValuesForDimension(
        dimensionsId: Operations\DimensionsId::BrowserName,
        timespan: Operations\ListFilterValuesForDimensionTimespan::TwentyFourhours,
        filterby: 'browser_name:Chrome',
    );
    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
}
