<?php

declare(strict_types=1);

/**
 * Test runner for Create Media by URL tests
 * This provides an overview of the media creation test suite
 */
class CreateMediaTestRunner
{
    public static function runCreateMediaTests(): void
    {
        echo "FastPix SDK - Create Media by URL Tests\n";
        echo "=======================================\n\n";

        echo "Testing Media Creation Functionality:\n";
        echo "✓ Create Media by URL (as per README)\n";
        echo "✓ Different Access Policies (Public/Private)\n";
        echo "✓ Multiple Video Inputs\n";
        echo "✓ Extensive Metadata\n";
        echo "✓ Error Handling (Invalid URL)\n\n";

        echo "Test Configuration:\n";
        echo "- Username: a157965b-eeb6-4f0e-9915-23f267e48017\n";
        echo "- Password: fe935c6a-03d0-42f0-af34-6de86b6b2450\n";
        echo "- Sample URL: https://static.fastpix.io/sample.mp4\n";
        echo "- Access Policy: Public/Private\n";
        echo "- Metadata: Various test scenarios\n\n";

        echo "To run these tests:\n";
        echo "./vendor/bin/phpunit Tests/Integration/CreateMediaByURLTest.php\n\n";

        echo "Individual test methods:\n";
        echo "- testCreateMediaByURLAsPerREADME() [LOGS MEDIA CREATION]\n";
        echo "- testCreateMediaWithDifferentAccessPolicies()\n";
        echo "- testCreateMediaWithMultipleInputs()\n";
        echo "- testCreateMediaWithExtensiveMetadata()\n";
        echo "- testCreateMediaWithInvalidURL()\n\n";
    }

    /**
     * Display media creation test documentation
     */
    public static function displayDocumentation(): void
    {
        echo "Create Media by URL Test Documentation\n";
        echo "=====================================\n\n";

        echo "This test suite validates media creation functionality using URLs.\n\n";

        echo "COVERED FUNCTIONALITY:\n\n";

        echo "1. Basic Media Creation\n";
        echo "   - Tests the main example from README\n";
        echo "   - Validates VideoInput, CreateMediaRequest, and response handling\n";
        echo "   - Uses exact same code structure as README\n";
        echo "   - Logs complete response details\n\n";

        echo "2. Access Policy Testing\n";
        echo "   - Tests both Public and Private access policies\n";
        echo "   - Validates different access control scenarios\n";
        echo "   - Ensures proper policy handling\n\n";

        echo "3. Multiple Inputs\n";
        echo "   - Tests creating media with multiple video inputs\n";
        echo "   - Validates array handling for inputs\n";
        echo "   - Tests bulk media processing\n\n";

        echo "4. Extensive Metadata\n";
        echo "   - Tests with comprehensive metadata\n";
        echo "   - Validates various metadata types\n";
        echo "   - Tests metadata persistence\n\n";

        echo "5. Error Handling\n";
        echo "   - Tests with invalid URLs\n";
        echo "   - Validates graceful error handling\n";
        echo "   - Tests exception scenarios\n\n";

        echo "TEST DATA:\n";
        echo "- Primary URL: https://static.fastpix.io/sample.mp4\n";
        echo "- Secondary URL: https://static.fastpix.io/sample2.mp4\n";
        echo "- Invalid URL: https://invalid-url-that-does-not-exist.com/video.mp4\n";
        echo "- Metadata: Various test scenarios including extensive metadata\n";
        echo "- Access Policies: Public, Private\n\n";

        echo "EXPECTED BEHAVIOR:\n";
        echo "- All valid requests should return 200 status codes\n";
        echo "- Media creation should return success responses with media IDs\n";
        echo "- Response should include input details and metadata\n";
        echo "- Error handling should catch and validate exceptions\n";
        echo "- All response data should be properly structured\n\n";

        echo "LOGGING FEATURES:\n";
        echo "- Complete media creation response logging\n";
        echo "- Input details and status logging\n";
        echo "- Metadata validation logging\n";
        echo "- Error scenario logging\n";
        echo "- Response structure validation\n\n";
    }
}

// Run the test overview if this file is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    CreateMediaTestRunner::runCreateMediaTests();
    echo "\n";
    CreateMediaTestRunner::displayDocumentation();
}
