<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

// Format a DateTime to an ISO string matching the API format ("2026-01-27T11:45:25.434248Z")
function normalizeDateTime(\DateTimeInterface $dt): string {
    $formatted = $dt->format('Y-m-d\TH:i:s.u');
    $timezone = $dt->getTimezone();
    $isUtc = $timezone->getName() === 'UTC'
        || $timezone->getName() === '+00:00'
        || $timezone->getOffset($dt) === 0;

    return $formatted . ($isUtc ? 'Z' : $timezone->format('P'));
}

// Normalize response to match API format (enums as strings, DateTime as ISO strings)
function normalizeResponse($x) {
    if ($x instanceof \DateTimeInterface) {
        $result = normalizeDateTime($x);
    } elseif ($x instanceof \BackedEnum) {
        // Convert enums to their string/int value
        $result = $x->value;
    } elseif (is_array($x)) {
        $result = array_map('normalizeResponse', $x);
    } elseif (is_object($x)) {
        // Convert object to array recursively
        $result = [];
        foreach (get_object_vars($x) as $k => $v) {
            $result[$k] = normalizeResponse($v);
        }
    } else {
        // Scalars and null pass through unchanged
        $result = $x;
    }

    return $result;
}

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

    $response = $sdk->playlist->getAllPlaylists(
    limit: 5,
    offset: 1

);

    if ($response->getAllPlaylistsResponse !== null) {
        // Normalize the response to match API format (DateTime -> ISO string, enums -> strings)
        $normalized = normalizeResponse($response->getAllPlaylistsResponse);
        echo json_encode($normalized, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
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
