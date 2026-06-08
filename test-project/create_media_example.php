<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

try {
    // Get credentials from environment variables or use empty strings (you can hardcode them here)
    $username = getenv('FASTPIX_USERNAME') ?: $_ENV['FASTPIX_USERNAME'] ?? '';
    $password = getenv('FASTPIX_PASSWORD') ?: $_ENV['FASTPIX_PASSWORD'] ?? '';

    $sdk = Sdk\Fastpixsdk::builder()
        ->setSecurity(
            new Components\Security(
                username: $username,
                password: $password,
            )
        )
        ->build();

    $request = new Components\CreateMediaRequest(
        inputs: [
            new Components\PullVideoInput(),
        ],
        metadata: [
            'key1' => 'value1',
        ],
    );

    $response = $sdk->inputVideo->createMedia(
        request: $request
    );

    if ($response->createMediaSuccessResponse !== null) {
        $output = [
            'success' => true,
            'data' => json_decode(json_encode($response->createMediaSuccessResponse), true)
        ];
        echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        $output = [
            'success' => false,
            'message' => 'No response data'
        ];
        if (isset($response->error)) {
            $output['error'] = json_decode(json_encode($response->error), true);
        }
        echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
} catch (\Exception $e) {
    // Try to get response body from exception
    $errorBody = null;
    
    // Check if it's an APIException (has $body and $statusCode properties)
    if (property_exists($e, 'body') && property_exists($e, 'statusCode')) {
        $body = $e->body;
        $errorBody = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorBody = $body;
        }
    } elseif (method_exists($e, 'getResponse')) {
        // Fallback for other exception types with getResponse()
        $response = $e->getResponse();
        if ($response !== null) {
            $body = (string)$response->getBody();
            $errorBody = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorBody = $body;
            }
        }
    }
    
    // Output only the response
    if ($errorBody !== null) {
        echo json_encode($errorBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        // Fallback if no response body found
        echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
    
    exit(1);
}
