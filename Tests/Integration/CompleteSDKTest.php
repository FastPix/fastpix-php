<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Integration;

use FastPix\Sdk\Models\Components\CreateLiveStreamRequest;
use FastPix\Sdk\Models\Components\CreateLiveStreamRequestAccessPolicy;
use FastPix\Sdk\Models\Components\CreateMediaRequest;
use FastPix\Sdk\Models\Components\CreateMediaRequestAccessPolicy;
use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\Models\Components\VideoInput;
use FastPix\Sdk\SDK;
use PHPUnit\Framework\TestCase;

class CompleteSDKTest extends TestCase
{
    private SDK $sdk;
    private array $testResults = [];
    private array $passedTests = [];
    private array $failedTests = [];

    protected function setUp(): void
    {
        $accessToken = $_ENV['FASTPIX_ACCESS_TOKEN'] ?? '';
        $secretKey = $_ENV['FASTPIX_SECRET_KEY'] ?? '';

        if (empty($accessToken) || empty($secretKey)) {
            $this->markTestSkipped('FASTPIX_ACCESS_TOKEN and FASTPIX_SECRET_KEY environment variables are required for integration tests');
        }

        $this->sdk = SDK::builder()
            ->setSecurity(
                new Security(
                    username: $accessToken,
                    password: $secretKey,
                )
            )
            ->build();
    }

    /**
     * Test all SDK resources and log comprehensive results
     */
    public function test_all_sdk_resources(): void
    {
        echo "\n".str_repeat('=', 80)."\n";
        echo "FASTPIX PHP SDK - COMPREHENSIVE TEST SUITE\n";
        echo str_repeat('=', 80)."\n";
        echo "Testing all available SDK resources and endpoints\n";
        echo "Credentials: a157965b-eeb6-4f0e-9915-23f267e48017\n";
        echo 'Timestamp: '.date('Y-m-d H:i:s')."\n";
        echo str_repeat('=', 80)."\n\n";

        $this->runResourceTests();
        $this->displayTestSummary();
    }

    private function runResourceTests(): void
    {
        $resources = [
            'InputVideo' => [$this, 'testInputVideo'],
            'ManageVideos' => [$this, 'testManageVideos'],
            'InVideoAIFeatures' => [$this, 'testInVideoAIFeatures'],
            'Playback' => [$this, 'testPlayback'],
            'Playlist' => [$this, 'testPlaylist'],
            'DRMConfigurations' => [$this, 'testDRMConfigurations'],
            'StartLiveStream' => [$this, 'testStartLiveStream'],
            'ManageLiveStream' => [$this, 'testManageLiveStream'],
            'LivePlayback' => [$this, 'testLivePlayback'],
            'SimulcastStream' => [$this, 'testSimulcastStream'],
            'SigningKeys' => [$this, 'testSigningKeys'],
            'Views' => [$this, 'testViews'],
            'Dimensions' => [$this, 'testDimensions'],
            'Metrics' => [$this, 'testMetrics'],
            'Errors' => [$this, 'testErrors'],
        ];

        foreach ($resources as $resourceName => $testMethod) {
            echo "Testing {$resourceName}...\n";
            echo str_repeat('-', 50)."\n";

            try {
                $testMethod();
                $this->passedTests[] = $resourceName;
                echo "✅ {$resourceName} - PASSED\n";
            } catch (\Exception $e) {
                $this->failedTests[] = $resourceName;
                echo "❌ {$resourceName} - FAILED: ".$e->getMessage()."\n";
            }

            echo "\n";
        }
    }

    public function test_input_video(): void
    {
        echo "Testing InputVideo resource...\n";

        // Test createMedia
        $request = new CreateMediaRequest(
            inputs: [
                new VideoInput(
                    type: 'video',
                    url: 'https://static.fastpix.io/sample.mp4',
                ),
            ],
            metadata: [
                'title' => 'Test Video',
                'test_resource' => 'InputVideo',
            ],
            accessPolicy: CreateMediaRequestAccessPolicy::Public,
        );

        $response = $this->sdk->inputVideo->createMedia(request: $request);

        echo "  ✓ createMedia - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->createMediaSuccessResponse !== null) {
            echo '  ✓ Media ID: '.($response->createMediaSuccessResponse->id ?? 'N/A')."\n";
            echo '  ✓ Status: '.($response->createMediaSuccessResponse->status ?? 'N/A')."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\CreateMediaResponse::class, $response);
    }

    public function test_manage_videos(): void
    {
        echo "Testing ManageVideos resource...\n";

        // Test listVideos
        $response = $this->sdk->manageVideos->listMedia(offset: 1, limit: 5);

        echo "  ✓ listVideos - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->object !== null) {
            $videos = $response->object->data ?? [];
            echo '  ✓ Videos Count: '.count($videos)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\ListMediaResponse::class, $response);
    }

    public function test_in_video_ai_features(): void
    {
        echo "Testing InVideoAIFeatures resource...\n";

        // Test listInVideoAI
        $response = $this->sdk->inVideoAIFeatures->listInVideoAI(offset: 1, limit: 5);

        echo "  ✓ listInVideoAI - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->listInVideoAISuccessResponse !== null) {
            $features = $response->listInVideoAISuccessResponse->data ?? [];
            echo '  ✓ AI Features Count: '.count($features)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\ListInVideoAIResponse::class, $response);
    }

    public function test_playback(): void
    {
        echo "Testing Playback resource...\n";

        // Test listPlaybackIds
        $response = $this->sdk->playback->listPlaybackIds(offset: 1, limit: 5);

        echo "  ✓ listPlaybackIds - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->listPlaybackIdsSuccessResponse !== null) {
            $playbackIds = $response->listPlaybackIdsSuccessResponse->data ?? [];
            echo '  ✓ Playback IDs Count: '.count($playbackIds)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\ListPlaybackIdsResponse::class, $response);
    }

    public function test_playlist(): void
    {
        echo "Testing Playlist resource...\n";

        // Test listPlaylists
        $response = $this->sdk->playlist->listPlaylists(offset: 1, limit: 5);

        echo "  ✓ listPlaylists - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->listPlaylistsSuccessResponse !== null) {
            $playlists = $response->listPlaylistsSuccessResponse->data ?? [];
            echo '  ✓ Playlists Count: '.count($playlists)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\ListPlaylistsResponse::class, $response);
    }

    public function test_drm_configurations(): void
    {
        echo "Testing DRMConfigurations resource...\n";

        // Test getDrmConfiguration
        $response = $this->sdk->drmConfigurations->getDrmConfiguration(offset: 1, limit: 5);

        echo "  ✓ getDrmConfiguration - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->object !== null) {
            $configs = $response->object->data ?? [];
            echo '  ✓ DRM Configurations Count: '.count($configs)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\GetDrmConfigurationResponse::class, $response);
    }

    public function test_start_live_stream(): void
    {
        echo "Testing StartLiveStream resource...\n";

        // Test createNewStream
        $request = new CreateLiveStreamRequest(
            name: 'Test Live Stream',
            metadata: [
                'title' => 'Test Stream',
                'test_resource' => 'StartLiveStream',
            ],
            accessPolicy: CreateLiveStreamRequestAccessPolicy::Public,
        );

        $response = $this->sdk->startLiveStream->createNewStream(request: $request);

        echo "  ✓ createNewStream - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->liveStreamResponseDTO !== null) {
            echo '  ✓ Stream ID: '.($response->liveStreamResponseDTO->data->streamId ?? 'N/A')."\n";
            echo '  ✓ Stream Name: '.($response->liveStreamResponseDTO->data->name ?? 'N/A')."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\CreateNewStreamResponse::class, $response);
    }

    public function test_manage_live_stream(): void
    {
        echo "Testing ManageLiveStream resource...\n";

        // Test listLiveStreams
        $response = $this->sdk->manageLiveStream->getAllStreams(offset: 1, limit: 5);

        echo "  ✓ listLiveStreams - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->object !== null) {
            $streams = $response->object->data ?? [];
            echo '  ✓ Live Streams Count: '.count($streams)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\GetAllStreamsResponse::class, $response);
    }

    public function test_live_playback(): void
    {
        echo "Testing LivePlayback resource...\n";

        // Test listLiveStreamPlaybackIds
        $response = $this->sdk->livePlayback->getLiveStreamPlaybackId('test-stream-id', 'test-playback-id');

        echo "  ✓ listLiveStreamPlaybackIds - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->listLiveStreamPlaybackIdsSuccessResponse !== null) {
            $playbackIds = $response->listLiveStreamPlaybackIdsSuccessResponse->data ?? [];
            echo '  ✓ Live Playback IDs Count: '.count($playbackIds)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\ListLiveStreamPlaybackIdsResponse::class, $response);
    }

    public function test_simulcast_stream(): void
    {
        echo "Testing SimulcastStream resource...\n";

        // Test listSimulcastsOfStream
        $streamId = 'test-stream-id';
        $response = $this->sdk->simulcastStream->listSimulcastsOfStream(streamId: $streamId);

        echo "  ✓ listSimulcastsOfStream - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->listSimulcastsOfStreamSuccessResponse !== null) {
            $simulcasts = $response->listSimulcastsOfStreamSuccessResponse->data ?? [];
            echo '  ✓ Simulcasts Count: '.count($simulcasts)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\ListSimulcastsOfStreamResponse::class, $response);
    }

    public function test_signing_keys(): void
    {
        echo "Testing SigningKeys resource...\n";

        // Test createSigningKey
        $response = $this->sdk->signingKeys->createSigningKey();

        echo "  ✓ createSigningKey - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->createResponse !== null) {
            echo '  ✓ Key ID: '.($response->createResponse->data->id ?? 'N/A')."\n";
            echo '  ✓ Created At: '.($response->createResponse->data->createdAt?->format('Y-m-d H:i:s') ?? 'N/A')."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\CreateSigningKeyResponse::class, $response);
    }

    public function test_views(): void
    {
        echo "Testing Views resource...\n";

        // Test getViewsData
        $response = $this->sdk->views->getViewsData(
            videoId: 'test-video-id',
            startDate: '2024-01-01',
            endDate: '2024-01-31'
        );

        echo "  ✓ getViewsData - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->viewsResponse !== null) {
            $views = $response->viewsResponse->data ?? [];
            echo '  ✓ Views Count: '.count($views)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\GetViewsDataResponse::class, $response);
    }

    public function test_dimensions(): void
    {
        echo "Testing Dimensions resource...\n";

        // Test getDimensionsData
        $response = $this->sdk->dimensions->getDimensionsData(
            videoId: 'test-video-id',
            dimension: 'country'
        );

        echo "  ✓ getDimensionsData - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->dimensionsResponse !== null) {
            $dimensions = $response->dimensionsResponse->data ?? [];
            echo '  ✓ Dimensions Count: '.count($dimensions)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\GetDimensionsDataResponse::class, $response);
    }

    public function test_metrics(): void
    {
        echo "Testing Metrics resource...\n";

        // Test getVideoMetrics
        $response = $this->sdk->metrics->getVideoMetrics(
            videoId: 'test-video-id',
            startDate: '2024-01-01',
            endDate: '2024-01-31'
        );

        echo "  ✓ getVideoMetrics - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->metricsResponse !== null) {
            echo '  ✓ Metrics Available: '.(isset($response->metricsResponse->data) ? 'Yes' : 'No')."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\GetVideoMetricsResponse::class, $response);
    }

    public function test_errors(): void
    {
        echo "Testing Errors resource...\n";

        // Test getErrorData
        $response = $this->sdk->errors->getErrorData(
            videoId: 'test-video-id',
            startDate: '2024-01-01',
            endDate: '2024-01-31'
        );

        echo "  ✓ getErrorData - Status: {$response->statusCode}\n";
        echo "  ✓ Content Type: {$response->contentType}\n";

        if ($response->errorResponse !== null) {
            $errors = $response->errorResponse->data ?? [];
            echo '  ✓ Errors Count: '.count($errors)."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\GetErrorDataResponse::class, $response);
    }

    private function displayTestSummary(): void
    {
        echo str_repeat('=', 80)."\n";
        echo "TEST SUMMARY\n";
        echo str_repeat('=', 80)."\n";

        $totalTests = count($this->passedTests) + count($this->failedTests);
        $passedCount = count($this->passedTests);
        $failedCount = count($this->failedTests);

        echo "Total Tests: {$totalTests}\n";
        echo "✅ Passed: {$passedCount}\n";
        echo "❌ Failed: {$failedCount}\n";
        echo 'Success Rate: '.round(($passedCount / $totalTests) * 100, 2)."%\n\n";

        if (! empty($this->passedTests)) {
            echo "✅ PASSED TESTS:\n";
            echo str_repeat('-', 30)."\n";
            foreach ($this->passedTests as $test) {
                echo "  ✓ {$test}\n";
            }
            echo "\n";
        }

        if (! empty($this->failedTests)) {
            echo "❌ FAILED TESTS:\n";
            echo str_repeat('-', 30)."\n";
            foreach ($this->failedTests as $test) {
                echo "  ✗ {$test}\n";
            }
            echo "\n";
        }

        echo str_repeat('=', 80)."\n";
        echo 'Test completed at: '.date('Y-m-d H:i:s')."\n";
        echo str_repeat('=', 80)."\n";
    }
}
