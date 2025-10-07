<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests;

/**
 * Signing Key Test Runner
 * 
 * Specialized test runner for signing key functionality tests.
 */
class SigningKeyTestRunner
{
    /**
     * Run all signing key related tests
     */
    public static function runSigningKeyTests(): void
    {
        echo "FastPix SDK - Signing Key Tests\n";
        echo "===============================\n\n";

        $signingKeyTests = [
            'Integration/SigningKeysTest.php' => 'Integration tests for signing key operations',
            'Unit/Models/Components/CreateSigningKeyResponseDTOTest.php' => 'Unit tests for CreateSigningKeyResponseDTO',
            'Unit/Models/Operations/CreateSigningKeyResponseTest.php' => 'Unit tests for CreateSigningKeyResponse',
        ];

        foreach ($signingKeyTests as $testFile => $description) {
            echo "Running: {$testFile}\n";
            echo "Description: {$description}\n";
            echo "Status: Ready to run with PHPUnit\n";
            echo "---\n";
        }

        echo "\nSigning Key Test Coverage:\n";
        echo "✓ Create signing key functionality\n";
        echo "✓ List signing keys with pagination\n";
        echo "✓ Get signing key by ID\n";
        echo "✓ Delete signing key\n";
        echo "✓ Response model validation\n";
        echo "✓ Error handling scenarios\n";
        echo "✓ Retry configuration\n";
        echo "✓ Security properties validation\n";
        echo "✓ Complete workflow testing\n\n";

        echo "To run these tests:\n";
        echo "./vendor/bin/phpunit Tests/Integration/SigningKeysTest.php\n";
        echo "./vendor/bin/phpunit Tests/Unit/Models/Components/CreateSigningKeyResponseDTOTest.php\n";
        echo "./vendor/bin/phpunit Tests/Unit/Models/Operations/CreateSigningKeyResponseTest.php\n";
        echo "\nOr run all signing key tests:\n";
        echo "./vendor/bin/phpunit --filter SigningKey\n";
    }

    /**
     * Display signing key test documentation
     */
    public static function showDocumentation(): void
    {
        echo "Signing Key Test Documentation\n";
        echo "=============================\n\n";

        echo "The signing key tests cover the complete lifecycle of cryptographic signing keys:\n\n";

        echo "1. CREATE SIGNING KEY\n";
        echo "   - Tests the creation of new RSA key pairs\n";
        echo "   - Validates response structure and properties\n";
        echo "   - Tests with different retry configurations\n\n";

        echo "2. LIST SIGNING KEYS\n";
        echo "   - Tests pagination functionality\n";
        echo "   - Validates request parameters\n";
        echo "   - Tests response structure\n\n";

        echo "3. GET SIGNING KEY BY ID\n";
        echo "   - Tests retrieval of specific keys\n";
        echo "   - Validates error handling for invalid IDs\n";
        echo "   - Tests response model structure\n\n";

        echo "4. DELETE SIGNING KEY\n";
        echo "   - Tests key deletion functionality\n";
        echo "   - Validates request parameters\n";
        echo "   - Tests response handling\n\n";

        echo "5. SECURITY VALIDATION\n";
        echo "   - Validates private key format\n";
        echo "   - Tests key ID generation\n";
        echo "   - Ensures proper encoding\n\n";

        echo "6. ERROR HANDLING\n";
        echo "   - Tests SigningKeyNotFoundError\n";
        echo "   - Validates authentication errors\n";
        echo "   - Tests invalid parameter handling\n\n";

        echo "7. INTEGRATION WORKFLOW\n";
        echo "   - Tests complete CRUD operations\n";
        echo "   - Validates end-to-end functionality\n";
        echo "   - Tests with real API responses (when authenticated)\n\n";
    }
}

// If this file is run directly, execute signing key tests
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    SigningKeyTestRunner::runSigningKeyTests();
    echo "\n";
    SigningKeyTestRunner::showDocumentation();
}
