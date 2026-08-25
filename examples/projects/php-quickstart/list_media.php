<?php

/**
 * Example: List media
 *
 * Copy this example from the README and modify as needed.
 */

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Errors;

try {
    $sdk = getSDK();

    // List media with pagination (offset starts at 1).
    $response = $sdk->manageVideos->listMedia(
        limit: 10,
        offset: 1,
        orderBy: Components\SortOrder::Desc,
    );

    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
} catch (\Throwable $e) {
    fwrite(STDERR, 'Error: ' . $e->getMessage() . "\n");
    exit(1);
}
