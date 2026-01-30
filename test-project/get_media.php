<?php
/**
 * Example: Get media by ID
 * 
 * Usage: php get_media.php <mediaId>
 */

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

try {
    $sdk = getSDK();

    // Get media ID from command line argument or use placeholder
    $mediaId = $argv[1] ?? 'your-media-id';

    if ($mediaId === 'your-media-id') {
        echo "Usage: php get_media.php <mediaId>\n";
        echo "Or set FASTPIX_MEDIA_ID environment variable\n";
        exit(1);
    }

    $response = $sdk->manageVideos->getMedia(mediaId: $mediaId);

    if ($response->statusCode >= 200 && $response->statusCode < 300) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo "Success!\n";
        echo ($decoded !== null ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $rawBody) . "\n";
    } else {
        $errorPayload = $response->defaultError ?? $response->error ?? null;
        if ($errorPayload !== null) {
            $err = json_decode(json_encode($errorPayload), true);
            echo json_encode($err, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        } else {
            echo "No response data\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (method_exists($e, 'getStatusCode')) {
        echo "Status Code: " . $e->getStatusCode() . "\n";
    }
    exit(1);
}
