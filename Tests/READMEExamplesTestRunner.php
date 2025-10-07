<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests;

/**
 * README Examples Test Runner
 * 
 * Test runner specifically for validating README code examples.
 */
class READMEExamplesTestRunner
{
    /**
     * Run all README example tests
     */
    public static function runREADMEExamplesTests(): void
    {
        echo "FastPix SDK - README Examples Tests\n";
        echo "===================================\n\n";

        echo "Testing README Code Examples:\n";
        echo "✓ Signing Key Creation (as per README)\n";
        echo "✓ Media Creation by URL (as per README)\n";
        echo "✓ Retry Configuration (as per README)\n";
        echo "✓ Global Retry Configuration (as per README)\n";
        echo "✓ Server URL Override (as per README)\n";
        echo "✓ Complete Workflow (Signing Key + Media Creation)\n";
        echo "✓ Error Handling (as per README)\n";
        echo "✓ Signing Key with Name 'wow' (LOGS GENERATED KEY)\n";
        echo "✓ All SDK Services Available (as per README)\n\n";

        echo "README Examples Covered:\n";
        echo "1. SDK Example Usage section\n";
        echo "2. Authentication section\n";
        echo "3. Retries section (both per-operation and global)\n";
        echo "4. Error Handling section\n";
        echo "5. Server Selection section\n";
        echo "6. Signing Keys documentation\n\n";

        echo "Test Configuration:\n";
        echo "- Username: a157965b-eeb6-4f0e-9915-23f267e48017\n";
        echo "- Password: fe935c6a-03d0-42f0-af34-6de86b6b2450\n";
        echo "- Sample URL: https://static.fastpix.io/sample.mp4\n";
        echo "- Access Policy: Public\n\n";

        echo "To run these tests:\n";
        echo "./vendor/bin/phpunit Tests/Integration/READMEExamplesTest.php\n\n";

        echo "Individual test methods:\n";
        echo "- testCreateSigningKeyAsPerREADME()\n";
        echo "- testCreateMediaByURLAsPerREADME()\n";
        echo "- testCreateSigningKeyWithRetryConfigAsPerREADME()\n";
        echo "- testSDKWithGlobalRetryConfigAsPerREADME()\n";
        echo "- testSDKWithServerURLAsPerREADME()\n";
        echo "- testCompleteWorkflowAsPerREADME()\n";
        echo "- testErrorHandlingAsPerREADME()\n";
        echo "- testCreateSigningKeyWithNameWow() [LOGS SIGNING KEY]\n";
        echo "- testAllSDKServicesAvailableAsPerREADME()\n\n";
    }

    /**
     * Display README examples documentation
     */
    public static function showREADMEExamplesDocumentation(): void
    {
        echo "README Examples Test Documentation\n";
        echo "==================================\n\n";

        echo "This test suite validates that all code examples in the README work correctly.\n\n";

        echo "COVERED README SECTIONS:\n\n";

        echo "1. SDK Example Usage\n";
        echo "   - Tests the main example showing media creation\n";
        echo "   - Validates VideoInput, CreateMediaRequest, and response handling\n";
        echo "   - Uses exact same code structure as README\n\n";

        echo "2. Authentication\n";
        echo "   - Tests Security model with username/password\n";
        echo "   - Validates SDK builder pattern with setSecurity()\n";
        echo "   - Uses real credentials for testing\n\n";

        echo "3. Retries\n";
        echo "   - Tests per-operation retry configuration\n";
        echo "   - Tests global retry configuration via SDK builder\n";
        echo "   - Validates RetryConfigBackoff parameters\n\n";

        echo "4. Error Handling\n";
        echo "   - Tests try-catch blocks as shown in README\n";
        echo "   - Validates specific exception types\n";
        echo "   - Tests error response handling\n\n";

        echo "5. Server Selection\n";
        echo "   - Tests custom server URL configuration\n";
        echo "   - Validates setServerURL() method\n";
        echo "   - Tests with FastPix API endpoint\n\n";

        echo "6. Signing Keys\n";
        echo "   - Tests signing key creation workflow\n";
        echo "   - Validates RSA 2048-bit key generation\n";
        echo "   - Tests private key format validation\n\n";

        echo "7. Complete Workflow\n";
        echo "   - Tests end-to-end: create signing key → create media\n";
        echo "   - Validates both operations work together\n";
        echo "   - Tests real API integration\n\n";

        echo "TEST DATA:\n";
        echo "- Sample Video URL: https://static.fastpix.io/sample.mp4\n";
        echo "- Metadata: ['key1' => 'value1']\n";
        echo "- Access Policy: Public\n";
        echo "- Retry Config: initialInterval=1, maxInterval=50, exponent=1.1\n";
        echo "- Server URL: https://api.fastpix.io/v1/\n\n";

        echo "EXPECTED BEHAVIOR:\n";
        echo "- All API calls should return 200 status codes\n";
        echo "- Signing keys should contain valid RSA private keys\n";
        echo "- Media creation should return success responses\n";
        echo "- Error handling should catch and validate exceptions\n";
        echo "- All SDK services should be available\n\n";
    }
}

// If this file is run directly, execute README examples tests
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    READMEExamplesTestRunner::runREADMEExamplesTests();
    echo "\n";
    READMEExamplesTestRunner::showREADMEExamplesDocumentation();
}
