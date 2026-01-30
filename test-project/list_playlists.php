<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

// Normalize response to match API format (enums as strings, DateTime as ISO strings)
function normalize_response($x) {
    // Handle null
    if ($x === null) {
        return null;
    }
    
    // Handle DateTime objects - convert to ISO string matching API format
    if ($x instanceof \DateTime || $x instanceof \DateTimeInterface) {
        // Format to match API: "2026-01-27T11:45:25.434248Z"
        $formatted = $x->format('Y-m-d\TH:i:s.u');
        $timezone = $x->getTimezone();
        if ($timezone->getName() === 'UTC' || $timezone->getName() === '+00:00' || $timezone->getOffset($x) === 0) {
            return $formatted . 'Z';
        }
        return $formatted . $timezone->format('P'); // Fallback to +00:00 format
    }
    
    // Handle enums (BackedEnum) - convert to their string/int value
    if (is_object($x) && ($x instanceof \BackedEnum)) {
        return $x->value;
    }
    
    // Handle arrays
    if (is_array($x)) {
        return array_map('normalize_response', $x);
    }
    
    // Handle objects
    if (is_object($x)) {
        // Convert object to array recursively
        $arr = [];
        $vars = get_object_vars($x);
        foreach ($vars as $k => $v) {
            $arr[$k] = normalize_response($v);
        }
        return $arr;
    }
    
    return $x;
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
        $normalized = normalize_response($response->getAllPlaylistsResponse);
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