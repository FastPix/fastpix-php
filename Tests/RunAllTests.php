<?php

declare(strict_types=1);

/**
 * Simple Test Runner for FastPix PHP SDK
 * Executes all tests and provides clear pass/fail results
 */

require_once __DIR__.'/../vendor/autoload.php';

use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\SDK;

class SimpleTestRunner
{
    private array $results = [];
    private int $passed = 0;
    private int $failed = 0;
    private int $skipped = 0;

    public function runAllTests(): void
    {
        echo "\n".str_repeat('=', 80)."\n";
        echo "FASTPIX PHP SDK - SIMPLE TEST RUNNER\n";
        echo str_repeat('=', 80)."\n";
        echo "Testing all SDK resources with real API calls\n";
        echo 'Timestamp: '.date('Y-m-d H:i:s')."\n";
        echo str_repeat('=', 80)."\n\n";

        $this->runSDKTests();
        $this->displayResults();
    }

    private function runSDKTests(): void
    {
        try {
            // Initialize SDK
            $sdk = SDK::builder()
                ->setSecurity(
                    new Security(
                        username: 'your-access-token',
                        password: 'your-secret-key',
                    )
                )
                ->build();

            echo "✅ SDK initialized successfully\n\n";

            // Test all resources
            $this->testResource('InputVideo', function () use ($sdk) {
                $request = new \FastPix\Sdk\Models\Components\CreateMediaRequest(
                    inputs: [
                        new \FastPix\Sdk\Models\Components\VideoInput(
                            type: 'video',
                            url: 'https://static.fastpix.io/sample.mp4',
                        ),
                    ],
                    metadata: ['title' => 'Test Video'],
                    accessPolicy: \FastPix\Sdk\Models\Components\CreateMediaRequestAccessPolicy::Public,
                );

                return $sdk->inputVideo->createMedia(request: $request);
            });

            $this->testResource('ManageVideos', function () use ($sdk) {
                return $sdk->manageVideos->listVideos(offset: 1, limit: 5);
            });

            $this->testResource('SigningKeys', function () use ($sdk) {
                return $sdk->signingKeys->createSigningKey();
            });

            $this->testResource('DRMConfigurations', function () use ($sdk) {
                return $sdk->drmConfigurations->getDrmConfiguration(offset: 1, limit: 5);
            });

            $this->testResource('StartLiveStream', function () use ($sdk) {
                $request = new \FastPix\Sdk\Models\Components\CreateLiveStreamRequest(
                    name: 'Test Stream',
                    metadata: ['title' => 'Test Live Stream'],
                    accessPolicy: \FastPix\Sdk\Models\Components\CreateLiveStreamRequestAccessPolicy::Public,
                );

                return $sdk->startLiveStream->createNewStream(request: $request);
            });

            $this->testResource('ManageLiveStream', function () use ($sdk) {
                return $sdk->manageLiveStream->listLiveStreams(offset: 1, limit: 5);
            });

            $this->testResource('Playback', function () use ($sdk) {
                return $sdk->playback->listPlaybackIds(offset: 1, limit: 5);
            });

            $this->testResource('Playlist', function () use ($sdk) {
                return $sdk->playlist->listPlaylists(offset: 1, limit: 5);
            });

            $this->testResource('Views', function () use ($sdk) {
                return $sdk->views->getViewsData(
                    videoId: 'test-video-id',
                    startDate: '2024-01-01',
                    endDate: '2024-01-31'
                );
            });

            $this->testResource('Metrics', function () use ($sdk) {
                return $sdk->metrics->getVideoMetrics(
                    videoId: 'test-video-id',
                    startDate: '2024-01-01',
                    endDate: '2024-01-31'
                );
            });

            $this->testResource('Dimensions', function () use ($sdk) {
                return $sdk->dimensions->getDimensionsData(
                    videoId: 'test-video-id',
                    dimension: 'country'
                );
            });

            $this->testResource('Errors', function () use ($sdk) {
                return $sdk->errors->getErrorData(
                    videoId: 'test-video-id',
                    startDate: '2024-01-01',
                    endDate: '2024-01-31'
                );
            });

        } catch (\Exception $e) {
            echo '❌ SDK Initialization Failed: '.$e->getMessage()."\n";
            $this->failed++;
        }
    }

    private function testResource(string $resourceName, callable $testFunction): void
    {
        echo "Testing {$resourceName}... ";

        try {
            $response = $testFunction();

            if ($response && isset($response->statusCode)) {
                echo "✅ PASSED (Status: {$response->statusCode})\n";
                $this->results[$resourceName] = [
                    'status' => 'PASSED',
                    'statusCode' => $response->statusCode,
                    'contentType' => $response->contentType ?? 'N/A',
                ];
                $this->passed++;
            } else {
                echo "⚠️  SKIPPED (No response)\n";
                $this->results[$resourceName] = [
                    'status' => 'SKIPPED',
                    'reason' => 'No response received',
                ];
                $this->skipped++;
            }
        } catch (\Exception $e) {
            echo '❌ FAILED ('.$e->getMessage().")\n";
            $this->results[$resourceName] = [
                'status' => 'FAILED',
                'error' => $e->getMessage(),
            ];
            $this->failed++;
        }
    }

    private function displayResults(): void
    {
        $total = $this->passed + $this->failed + $this->skipped;

        echo "\n".str_repeat('=', 80)."\n";
        echo "TEST RESULTS SUMMARY\n";
        echo str_repeat('=', 80)."\n";
        echo "Total Tests: {$total}\n";
        echo "✅ Passed: {$this->passed}\n";
        echo "❌ Failed: {$this->failed}\n";
        echo "⚠️  Skipped: {$this->skipped}\n";

        if ($total > 0) {
            $successRate = round(($this->passed / $total) * 100, 2);
            echo "Success Rate: {$successRate}%\n";
        }

        echo "\nDETAILED RESULTS:\n";
        echo str_repeat('-', 50)."\n";

        foreach ($this->results as $resource => $result) {
            $status = $result['status'];
            $icon = $status === 'PASSED' ? '✅' : ($status === 'FAILED' ? '❌' : '⚠️');

            echo "{$icon} {$resource}: {$status}";

            if (isset($result['statusCode'])) {
                echo " (HTTP {$result['statusCode']})";
            }
            if (isset($result['error'])) {
                echo " - {$result['error']}";
            }
            if (isset($result['reason'])) {
                echo " - {$result['reason']}";
            }

            echo "\n";
        }

        echo "\n".str_repeat('=', 80)."\n";
        echo 'Test completed at: '.date('Y-m-d H:i:s')."\n";
        echo str_repeat('=', 80)."\n";

        // Exit with appropriate code
        if ($this->failed > 0) {
            exit(1);
        } else {
            exit(0);
        }
    }
}

// Run the tests
$runner = new SimpleTestRunner();
$runner->runAllTests();
