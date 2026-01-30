<?php
/**
 * Example: List signing keys
 */

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

try {
    $sdk = getSDK();

    $response = $sdk->signingKeys->listSigningKeys(
        limit: 10,
        offset: 0
    );

    if ($response->listSigningKeysResponse !== null) {
        echo "Success!\n";
        echo "Total signing keys: " . count($response->listSigningKeysResponse->data ?? []) . "\n";
        echo json_encode($response->listSigningKeysResponse, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "No response data\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
