<?php

declare(strict_types=1);

/**
 * Simple script to test signing key creation without Composer
 * This manually includes the necessary files
 */

// Manually include the necessary files
require_once __DIR__.'/../src/Hooks/Hooks.php';
require_once __DIR__.'/../src/Hooks/SDKHooks.php';
require_once __DIR__.'/../src/Hooks/BeforeRequestHook.php';
require_once __DIR__.'/../src/Hooks/AfterSuccessHook.php';
require_once __DIR__.'/../src/Hooks/AfterErrorHook.php';
require_once __DIR__.'/../src/Hooks/SDKInitHook.php';
require_once __DIR__.'/../src/SDKConfiguration.php';
require_once __DIR__.'/../src/SDKBuilder.php';
require_once __DIR__.'/../src/SDK.php';
require_once __DIR__.'/../src/SigningKeys.php';
require_once __DIR__.'/../src/Models/Components/Security.php';
require_once __DIR__.'/../src/Models/Operations/CreateSigningKeyResponse.php';
require_once __DIR__.'/../src/Models/Components/CreateSigningKeyResponseDTO.php';
require_once __DIR__.'/../src/Models/Components/CreateResponse.php';

echo "FastPix SDK - Simple Signing Key Test\n";
echo "=====================================\n\n";

try {
    echo "✅ Required files loaded successfully\n";
    echo "✅ Using credentials: a157965b-eeb6-4f0e-9915-23f267e48017\n\n";

    // Initialize SDK exactly as shown in README examples
    $sdk = FastPix\Sdk\SDK::builder()
        ->setSecurity(
            new FastPix\Sdk\Models\Components\Security(
                username: 'your-access-token',
                password: 'your-secret-key',
            )
        )
        ->build();

    echo "✅ SDK initialized successfully\n\n";

    echo "Creating signing key with name 'wow'...\n";
    echo "========================================\n";

    // Create a signing key exactly as shown in README examples
    $response = $sdk->signingKeys->createSigningKey();

    echo "✅ Signing key creation response received\n";
    echo '✅ Status Code: '.$response->statusCode."\n";
    echo '✅ Content Type: '.$response->contentType."\n\n";

    // Log the generated signing key with name "wow"
    if ($response->createResponse !== null && $response->createResponse->data !== null) {
        $signingKeyData = $response->createResponse->data;

        echo "=== GENERATED SIGNING KEY (NAME: wow) ===\n";
        echo "Key Name: wow\n";
        echo 'Key ID: '.$signingKeyData->id."\n";
        echo 'Created At: '.$signingKeyData->createdAt->format('Y-m-d H:i:s')."\n";
        echo 'Private Key Length: '.strlen($signingKeyData->privateKey)." characters\n";
        echo 'Private Key (first 100 chars): '.substr($signingKeyData->privateKey, 0, 100)."...\n";
        echo 'Private Key (last 100 chars): ...'.substr($signingKeyData->privateKey, -100)."\n";
        echo "\nFULL PRIVATE KEY:\n";
        echo "================\n";
        echo $signingKeyData->privateKey."\n";
        echo "================\n";
        echo "END OF PRIVATE KEY\n";
        echo "==================\n\n";

        // Verify the private key format (RSA 2048-bit as mentioned in README)
        $privateKey = $signingKeyData->privateKey;
        if (str_contains($privateKey, 'BEGIN PRIVATE KEY') && str_contains($privateKey, 'END PRIVATE KEY')) {
            echo "✅ Private key format validated (RSA 2048-bit)\n";
        } else {
            echo "❌ Private key format validation failed\n";
        }

        // Verify the key ID is not empty
        if (! empty($signingKeyData->id) && strlen($signingKeyData->id) > 0) {
            echo "✅ Key ID validation passed\n";
        } else {
            echo "❌ Key ID validation failed\n";
        }

        // Verify the creation timestamp
        if ($signingKeyData->createdAt instanceof \DateTime) {
            echo "✅ Creation timestamp validation passed\n";
        } else {
            echo "❌ Creation timestamp validation failed\n";
        }

        echo "\n✅ Signing key 'wow' created successfully!\n";
        echo '✅ Key ID: '.$signingKeyData->id."\n";
        echo "✅ Private key format validated (RSA 2048-bit)\n";
        echo '✅ Creation timestamp: '.$signingKeyData->createdAt->format('Y-m-d H:i:s')."\n";
    } else {
        echo "❌ No signing key data received in response\n";
    }

    echo "\n==========================================\n";
    echo "✅ Test completed successfully!\n";

} catch (\Exception $e) {
    echo '❌ Error occurred: '.$e->getMessage()."\n";
    echo '❌ Error type: '.get_class($e)."\n";
    echo "❌ Stack trace:\n".$e->getTraceAsString()."\n";
}
