<?php

/**
 * Example: Get media by ID
 *
 * Usage: php get_media.php <mediaId>
 */

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use FastPix\Sdk\Models\Errors;

$mediaId = $argv[1] ?? null;
if ($mediaId === null) {
    fwrite(STDERR, "Usage: php get_media.php <mediaId>\n");
    exit(1);
}

try {
    $sdk = getSDK();
    $response = $sdk->manageVideos->getMedia(mediaId: $mediaId);
    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
} catch (\Throwable $e) {
    fwrite(STDERR, 'Error: ' . $e->getMessage() . "\n");
    exit(1);
}
