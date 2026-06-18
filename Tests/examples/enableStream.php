<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

try {
    $sdk = Sdk\Fastpixsdk::builder()
        ->setSecurity(
            new Components\Security(
                username: '1b92c0d6-5548-4642-b13e-4bb7d77dbaf4',
                password: 'ff32012b-ec02-40ca-b0d4-711d81537e73',
            )
        )
        ->build();

    // The ID of the stream to enable
    $streamId = 'edfee27d56163db54ff72d8199392532';

    $response = $sdk->manageLiveStream->enableLiveStream(
        streamId: $streamId,
    );

    if ($response->statusCode >= 200 && $response->statusCode < 300) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo ($decoded !== null ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $rawBody) . "\n";
    } else {
        $errorPayload = $response->defaultError ?? $response->error ?? null;
        if ($errorPayload !== null) {
            $errorResponse = json_decode(json_encode($errorPayload), true);
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