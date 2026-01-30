<?php
declare(strict_types=1);

// require 'vendor/autoload.php';
require __DIR__ . '/../vendor/autoload.php';


use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;

try {
    // Use environment variables or replace with your credentials
    $username = getenv('FASTPIX_USERNAME') ?: $_ENV['FASTPIX_USERNAME'] ?? 'your-access-token';
    $password = getenv('FASTPIX_PASSWORD') ?: $_ENV['FASTPIX_PASSWORD'] ?? 'your-secret-key';

    $sdk = Sdk\Fastpixsdk::builder()
        ->setSecurity(
            new Components\Security(
                username: $username,
                password: $password,
            )
        )
        ->build();

    // Media ID to update summary for — pass as first CLI argument: php update_media_summary.php <mediaId>
    $mediaId = $argv[1] ?? 'your-media-id';

    $body = new Operations\UpdateMediaSummaryRequestBody(
        generate: true);

    $response = $sdk->inVideoAIFeatures->updateMediaSummary(
        body: $body,
        mediaId: $mediaId,
    );

    if ($response->object !== null) {
        $apiResponse = json_decode(json_encode($response->object), true);
        echo json_encode($apiResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        if (isset($response->error)) {
            $errorResponse = json_decode(json_encode($response->error), true);
            echo json_encode($errorResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        } else {
            echo json_encode(['message' => 'No response data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        }
    }
} catch (\Exception $e) {
    // Extract API error response
    $errorBody = null;
    if (property_exists($e, 'body') && property_exists($e, 'statusCode')) {
        $body = $e->body;
        $errorBody = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorBody = $body;
        }
    } elseif (method_exists($e, 'getResponse')) {
        $response = $e->getResponse();
        if ($response !== null) {
            $body = (string)$response->getBody();
            $errorBody = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorBody = $body;
            }
        }
    }
    
    // Output API error response
    if ($errorBody !== null) {
        echo json_encode($errorBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
    exit(1);
}