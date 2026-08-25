<?php

declare(strict_types=1);

// Generate an AI summary for an existing READY media.
// Run: php update-media-summary.php <mediaId>
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

// Summaries only work on media that has finished processing (status "Ready").
$mediaId = $argv[1] ?? 'your-ready-media-id';

try {
    $response = $sdk->inVideoAIFeatures->updateMediaSummary(
        body: new Operations\UpdateMediaSummaryRequestBody(generate: true, summaryLength: 100),
        mediaId: $mediaId,
    );
    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
}
