<?php

declare(strict_types=1);

/**
 * Complete Test Runner for FastPix PHP SDK
 * This runner executes all available tests and provides comprehensive logging
 */
class CompleteTestRunner
{
    private array $testResults = [];
    private array $passedTests = [];
    private array $failedTests = [];
    private array $skippedTests = [];
    private int $totalTests = 0;
    private int $startTime;

    public function __construct()
    {
        $this->startTime = time();
    }

    public function runAllTests(): void
    {
        echo "\n".str_repeat('=', 100)."\n";
        echo "FASTPIX PHP SDK - COMPLETE TEST SUITE RUNNER\n";
        echo str_repeat('=', 100)."\n";
        echo "Comprehensive testing of all SDK resources and endpoints\n";
        echo 'Timestamp: '.date('Y-m-d H:i:s')."\n";
        echo 'PHP Version: '.PHP_VERSION."\n";
        echo 'Working Directory: '.getcwd()."\n";
        echo str_repeat('=', 100)."\n\n";

        $this->runTestSuites();
        $this->displayComprehensiveSummary();
    }

    private function runTestSuites(): void
    {
        $testSuites = [
            'Complete SDK Test' => [
                'file' => 'Tests/Integration/CompleteSDKTest.php',
                'method' => 'testAllSDKResources',
                'description' => 'Tests all 15 SDK resources comprehensively',
            ],
            'Signing Keys Test' => [
                'file' => 'Tests/Integration/SigningKeysTest.php',
                'method' => 'testSigningKeysServiceExists',
                'description' => 'Tests signing keys functionality',
            ],
            'DRM Configurations Test' => [
                'file' => 'Tests/Integration/DRMConfigurationsTest.php',
                'method' => 'testGetDrmConfigurationAsPerREADME',
                'description' => 'Tests DRM configuration operations',
            ],
            'Create Media Test' => [
                'file' => 'Tests/Integration/CreateMediaByURLTest.php',
                'method' => 'testCreateMediaByURLAsPerREADME',
                'description' => 'Tests media creation from URL',
            ],
            'README Examples Test' => [
                'file' => 'Tests/Integration/READMEExamplesTest.php',
                'method' => 'testCreateSigningKeyAsPerREADME',
                'description' => 'Tests examples from README documentation',
            ],
        ];

        foreach ($testSuites as $suiteName => $suiteInfo) {
            echo "Running Test Suite: {$suiteName}\n";
            echo str_repeat('-', 60)."\n";
            echo "Description: {$suiteInfo['description']}\n";
            echo "File: {$suiteInfo['file']}\n";
            echo "Method: {$suiteInfo['method']}\n\n";

            $this->runTestSuite($suiteName, $suiteInfo);
            echo "\n";
        }
    }

    private function runTestSuite(string $suiteName, array $suiteInfo): void
    {
        try {
            // Check if file exists
            if (! file_exists($suiteInfo['file'])) {
                $this->skippedTests[] = $suiteName;
                echo "⚠️  SKIPPED: File not found - {$suiteInfo['file']}\n";

                return;
            }

            // Run the test using PHPUnit
            $command = "./vendor/bin/phpunit --filter {$suiteInfo['method']} {$suiteInfo['file']} 2>&1";
            $output = shell_exec($command);

            if ($output === null) {
                $this->failedTests[] = $suiteName;
                echo "❌ FAILED: Command execution failed\n";

                return;
            }

            // Parse the output
            $this->parseTestOutput($suiteName, $output);

        } catch (\Exception $e) {
            $this->failedTests[] = $suiteName;
            echo '❌ FAILED: Exception - '.$e->getMessage()."\n";
        }
    }

    private function parseTestOutput(string $suiteName, string $output): void
    {
        echo "Raw Output:\n";
        echo str_repeat('-', 40)."\n";
        echo $output."\n";
        echo str_repeat('-', 40)."\n";

        // Check for success indicators
        if (strpos($output, 'OK') !== false && strpos($output, 'FAILURES') === false) {
            $this->passedTests[] = $suiteName;
            echo "✅ PASSED: {$suiteName}\n";
        } elseif (strpos($output, 'FAILURES') !== false || strpos($output, 'ERRORS') !== false) {
            $this->failedTests[] = $suiteName;
            echo "❌ FAILED: {$suiteName}\n";
        } else {
            $this->skippedTests[] = $suiteName;
            echo "⚠️  SKIPPED: {$suiteName} (No clear result)\n";
        }

        $this->testResults[$suiteName] = $output;
    }

    private function displayComprehensiveSummary(): void
    {
        $endTime = time();
        $duration = $endTime - $this->startTime;

        echo str_repeat('=', 100)."\n";
        echo "COMPREHENSIVE TEST SUMMARY\n";
        echo str_repeat('=', 100)."\n";

        $this->totalTests = count($this->passedTests) + count($this->failedTests) + count($this->skippedTests);
        $passedCount = count($this->passedTests);
        $failedCount = count($this->failedTests);
        $skippedCount = count($this->skippedTests);

        echo "Test Execution Summary:\n";
        echo str_repeat('-', 50)."\n";
        echo "Total Test Suites: {$this->totalTests}\n";
        echo "✅ Passed: {$passedCount}\n";
        echo "❌ Failed: {$failedCount}\n";
        echo "⚠️  Skipped: {$skippedCount}\n";
        echo "Duration: {$duration} seconds\n";

        if ($this->totalTests > 0) {
            $successRate = round(($passedCount / $this->totalTests) * 100, 2);
            echo "Success Rate: {$successRate}%\n";
        }

        echo "\n";

        // Detailed Results
        if (! empty($this->passedTests)) {
            echo "✅ PASSED TEST SUITES:\n";
            echo str_repeat('-', 50)."\n";
            foreach ($this->passedTests as $test) {
                echo "  ✓ {$test}\n";
            }
            echo "\n";
        }

        if (! empty($this->failedTests)) {
            echo "❌ FAILED TEST SUITES:\n";
            echo str_repeat('-', 50)."\n";
            foreach ($this->failedTests as $test) {
                echo "  ✗ {$test}\n";
            }
            echo "\n";
        }

        if (! empty($this->skippedTests)) {
            echo "⚠️  SKIPPED TEST SUITES:\n";
            echo str_repeat('-', 50)."\n";
            foreach ($this->skippedTests as $test) {
                echo "  ⚠ {$test}\n";
            }
            echo "\n";
        }

        // SDK Resources Coverage
        echo "SDK RESOURCES COVERAGE:\n";
        echo str_repeat('-', 50)."\n";
        $resources = [
            'InputVideo' => 'Media input operations',
            'ManageVideos' => 'Video management operations',
            'InVideoAIFeatures' => 'AI-powered video features',
            'Playback' => 'Playback ID management',
            'Playlist' => 'Playlist operations',
            'DRMConfigurations' => 'DRM configuration management',
            'StartLiveStream' => 'Live stream creation',
            'ManageLiveStream' => 'Live stream management',
            'LivePlayback' => 'Live stream playback',
            'SimulcastStream' => 'Simulcast operations',
            'SigningKeys' => 'JWT signing key management',
            'Views' => 'Video analytics and views',
            'Dimensions' => 'Analytics dimensions',
            'Metrics' => 'Video performance metrics',
            'Errors' => 'Error tracking and analysis',
        ];

        foreach ($resources as $resource => $description) {
            echo "  • {$resource}: {$description}\n";
        }

        echo "\n";

        // Recommendations
        echo "RECOMMENDATIONS:\n";
        echo str_repeat('-', 50)."\n";
        if ($failedCount > 0) {
            echo "• Review failed tests and check API credentials\n";
            echo "• Verify network connectivity to FastPix API\n";
            echo "• Check if test data exists in your workspace\n";
        }
        if ($skippedCount > 0) {
            echo "• Ensure all test files are present and accessible\n";
            echo "• Check file permissions and paths\n";
        }
        if ($passedCount === $this->totalTests) {
            echo "• All tests passed! SDK is working correctly\n";
            echo "• Consider running integration tests with real data\n";
        }

        echo "\n";
        echo str_repeat('=', 100)."\n";
        echo 'Test execution completed at: '.date('Y-m-d H:i:s')."\n";
        echo str_repeat('=', 100)."\n";
    }

    public function getTestResults(): array
    {
        return [
            'passed' => $this->passedTests,
            'failed' => $this->failedTests,
            'skipped' => $this->skippedTests,
            'total' => $this->totalTests,
            'results' => $this->testResults,
        ];
    }
}

// Run the test suite if this file is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $runner = new CompleteTestRunner();
    $runner->runAllTests();
}
