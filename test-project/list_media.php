<?php
/**
 * Example: List media
 *
 * Copy this example from the README and modify as needed.
 */

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use FastPix\Sdk\Models\Components;

try {
    $sdk = getSDK();

    // List media with pagination
    $response = $sdk->manageVideos->listMedia(
        limit: 10,
        offset: 0,
        orderBy: Components\SortOrder::Desc
    );

    if ($response->listMediaResponse !== null) {
        echo "Success!\n";
        echo "Total items: " . count($response->listMediaResponse->data ?? []) . "\n";
        echo json_encode($response->listMediaResponse, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "No response data\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
