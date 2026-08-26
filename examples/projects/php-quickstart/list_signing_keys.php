<?php

/**
 * Example: List signing keys
 *
 * Note: listing signing keys needs an access token with system/admin permission.
 */

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use FastPix\Sdk\Models\Errors;

try {
    $sdk = getSDK();

    $response = $sdk->signingKeys->listSigningKeys(limit: 10, offset: 1);

    echo json_encode(json_decode((string) $response->rawResponse->getBody()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
} catch (Errors\APIException $e) {
    fwrite(STDERR, "HTTP {$e->statusCode}: {$e->body}\n");
    exit(1);
}
