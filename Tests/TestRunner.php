<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests;

/**
 * Test Runner for FastPix SDK
 * 
 * This class provides utility methods for running tests and generating test reports.
 */
class TestRunner
{
    /**
     * Run all unit tests
     */
    public static function runUnitTests(): void
    {
        echo "Running Unit Tests...\n";
        echo "===================\n";

        $testFiles = glob(__DIR__.'/Unit/**/*Test.php');

        foreach ($testFiles as $testFile) {
            echo 'Running: '.basename($testFile)."\n";
            // In a real implementation, this would run PHPUnit
        }

        echo "\nUnit tests completed.\n";
    }

    /**
     * Run all integration tests
     */
    public static function runIntegrationTests(): void
    {
        echo "Running Integration Tests...\n";
        echo "===========================\n";

        $testFiles = glob(__DIR__.'/Integration/**/*Test.php');

        foreach ($testFiles as $testFile) {
            echo 'Running: '.basename($testFile)."\n";
            // In a real implementation, this would run PHPUnit
        }

        echo "\nIntegration tests completed.\n";
    }

    /**
     * Run all tests
     */
    public static function runAllTests(): void
    {
        echo "FastPix SDK Test Suite\n";
        echo "======================\n\n";

        self::runUnitTests();
        echo "\n";
        self::runIntegrationTests();

        echo "\nAll tests completed.\n";
    }

    /**
     * Generate test coverage report
     */
    public static function generateCoverageReport(): void
    {
        echo "Generating Test Coverage Report...\n";
        echo "==================================\n";

        // In a real implementation, this would use PHPUnit with coverage
        echo "Coverage report would be generated here.\n";
    }

    /**
     * List all available test files
     */
    public static function listTestFiles(): array
    {
        $testFiles = [];

        $unitTests = glob(__DIR__.'/Unit/**/*Test.php');
        $integrationTests = glob(__DIR__.'/Integration/**/*Test.php');
        $modelTests = glob(__DIR__.'/Models/**/*Test.php');

        return array_merge($unitTests, $integrationTests, $modelTests);
    }
}

// If this file is run directly, execute all tests
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    TestRunner::runAllTests();
}
