<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;
use PHPUnit\Framework\TestCase;

class ManageLiveStreamTest extends TestCase
{
    private Sdk\FastPix $sdk;
    private ?string $testStreamId = null;

    protected function setUp(): void
    {
        parent::setUp();
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
        $this->sdk = Sdk\FastPix::builder()
            ->setSecurity(
                new Components\Security(
                    username: $_ENV['FASTPIX_USERNAME'],
                    password: $_ENV['FASTPIX_PASSWORD'],
                )
            )
            ->setServerUrl('https://v1.fastpix.io/live')
            ->build();
    }

    public function testCreateLiveStream(): void
    {
        $playbackSettings = new Components\PlaybackSettings(
            Components\PlaybackSettingsAccessPolicy::Public
        );
        $inputMediaSettings = new Components\InputMediaSettings(
            null, // metadata
            Components\CreateLiveStreamRequestMaxResolution::OneThousandAndEightyp,
            60, // reconnectWindow
            Components\MediaPolicy::Public
        );
        $request = new Components\CreateLiveStreamRequest(
            $playbackSettings,
            $inputMediaSettings
        );
        $response = $this->sdk->startLiveStream->createNewStream($request);
        $this->assertNotNull($response);
        $this->assertNotNull($response->liveStreamResponseDTO);
        $this->assertTrue($response->liveStreamResponseDTO->success);
        $this->assertNotNull($response->liveStreamResponseDTO->data);
        $this->assertNotNull($response->liveStreamResponseDTO->data->streamId);
        $this->testStreamId = $response->liveStreamResponseDTO->data->streamId;
        
        // Print the created stream details
        echo "\nCreated Live Stream:\n";
        echo json_encode($response->liveStreamResponseDTO->data, JSON_PRETTY_PRINT) . "\n";
    }

    public function testListLiveStreams(): void
    {
        $response = $this->sdk->manageLiveStream->getAllStreams();
        $this->assertNotNull($response);
        $this->assertNotNull($response->getStreamsResponse);
        $this->assertIsArray($response->getStreamsResponse->data ?? []);
        
        // Print the response data
        echo "\nList of Live Streams:\n";
        echo json_encode($response->getStreamsResponse->data, JSON_PRETTY_PRINT) . "\n";
    }

    public function testGetLiveStreamById(): void
    {
        if (empty($this->testStreamId)) {
            $this->markTestSkipped('No stream ID available for testing');
            return;
        }
        $response = $this->sdk->manageLiveStream->getLiveStreamById($this->testStreamId);
        $this->assertNotNull($response);
        $this->assertNotNull($response->livestreamgetResponse);
        $this->assertEquals($this->testStreamId, $response->livestreamgetResponse->streamId);
        
        // Print the response data
        echo "\nLive Stream Details:\n";
        echo json_encode($response->livestreamgetResponse, JSON_PRETTY_PRINT) . "\n";
    }

    public function testUpdateLiveStream(): void
    {
        if (empty($this->testStreamId)) {
            $this->markTestSkipped('No stream ID available for testing');
            return;
        }
        $patchRequest = new Components\PatchLiveStreamRequest(
            null, // metadata
            60 // reconnectWindow
        );
        $response = $this->sdk->manageLiveStream->updateLiveStream(
            $this->testStreamId,
            $patchRequest
        );
        $this->assertNotNull($response);
        $this->assertNotNull($response->patchResponseDTO);
        echo "\nUpdated Live Stream:\n";
        echo json_encode($response->patchResponseDTO, JSON_PRETTY_PRINT) . "\n";
    }

    public function testDeleteLiveStream(): void
    {
        if (empty($this->testStreamId)) {
            $this->markTestSkipped('No stream ID available for testing');
            return;
        }
        $response = $this->sdk->manageLiveStream->deleteLiveStream($this->testStreamId);
        $this->assertNotNull($response);
        $this->assertNotNull($response->liveStreamDeleteResponse);
        $this->assertTrue($response->liveStreamDeleteResponse->success ?? true);
    }

    public function testCreateAndGetLiveStreamById(): void
    {
        // Step 1: Create a live stream
        $playbackSettings = new Components\PlaybackSettings(
            Components\PlaybackSettingsAccessPolicy::Public
        );
        $inputMediaSettings = new Components\InputMediaSettings(
            null, // metadata
            Components\CreateLiveStreamRequestMaxResolution::OneThousandAndEightyp,
            60, // reconnectWindow
            Components\MediaPolicy::Public
        );
        $request = new Components\CreateLiveStreamRequest(
            $playbackSettings,
            $inputMediaSettings
        );
        $response = $this->sdk->startLiveStream->createNewStream($request);
        $this->assertNotNull($response);
        $this->assertNotNull($response->liveStreamResponseDTO);
        $this->assertTrue($response->liveStreamResponseDTO->success);
        $this->assertNotNull($response->liveStreamResponseDTO->data);
        $this->assertNotNull($response->liveStreamResponseDTO->data->streamId);
        $streamId = $response->liveStreamResponseDTO->data->streamId;
        
        // Print the created stream details
        echo "\nCreated Live Stream:\n";
        echo json_encode($response->liveStreamResponseDTO->data, JSON_PRETTY_PRINT) . "\n";

        // Step 2: Fetch the live stream by ID
        $fetchResponse = $this->sdk->manageLiveStream->getLiveStreamById($streamId);
        // Debug: print the entire fetch response
        echo "\nFull Fetch Response:\n";
        echo json_encode($fetchResponse, JSON_PRETTY_PRINT) . "\n";
        $this->assertNotNull($fetchResponse);
        $this->assertNotNull($fetchResponse->livestreamgetResponse);
        $this->assertEquals($streamId, $fetchResponse->livestreamgetResponse->data->streamId);
        
        // Print the fetched stream details
        echo "\nFetched Live Stream by ID:\n";
        echo json_encode($fetchResponse->livestreamgetResponse->data, JSON_PRETTY_PRINT) . "\n";
    }

    public function testFullLiveStreamLifecycle(): void
    {
        // Step 1: Create a live stream
        $playbackSettings = new Components\PlaybackSettings(
            Components\PlaybackSettingsAccessPolicy::Public
        );
        $inputMediaSettings = new Components\InputMediaSettings(
            null, // metadata
            Components\CreateLiveStreamRequestMaxResolution::OneThousandAndEightyp,
            60, // reconnectWindow
            Components\MediaPolicy::Public
        );
        $request = new Components\CreateLiveStreamRequest(
            $playbackSettings,
            $inputMediaSettings
        );
        $createResponse = $this->sdk->startLiveStream->createNewStream($request);
        $this->assertNotNull($createResponse);
        $this->assertNotNull($createResponse->liveStreamResponseDTO);
        $this->assertTrue($createResponse->liveStreamResponseDTO->success);
        $this->assertNotNull($createResponse->liveStreamResponseDTO->data);
        $this->assertNotNull($createResponse->liveStreamResponseDTO->data->streamId);
        $streamId = $createResponse->liveStreamResponseDTO->data->streamId;
        echo "\nCreated Live Stream:\n";
        echo json_encode($createResponse->liveStreamResponseDTO->data, JSON_PRETTY_PRINT) . "\n";

        // Step 2: Get the live stream by ID
        $fetchResponse = $this->sdk->manageLiveStream->getLiveStreamById($streamId);
        $this->assertNotNull($fetchResponse);
        $this->assertNotNull($fetchResponse->livestreamgetResponse);
        $this->assertEquals($streamId, $fetchResponse->livestreamgetResponse->data->streamId);
        echo "\nFetched Live Stream by ID:\n";
        echo json_encode($fetchResponse->livestreamgetResponse->data, JSON_PRETTY_PRINT) . "\n";

        // Step 3: Update the live stream
        $patchRequest = new Components\PatchLiveStreamRequest(
            null, // metadata
            60 // reconnectWindow
        );
        $updateResponse = $this->sdk->manageLiveStream->updateLiveStream(
            $streamId,
            $patchRequest
        );
        $this->assertNotNull($updateResponse);
        $this->assertNotNull($updateResponse->patchResponseDTO);
        echo "\nUpdated Live Stream:\n";
        echo json_encode($updateResponse->patchResponseDTO, JSON_PRETTY_PRINT) . "\n";

        // Step 4: Delete the live stream
        $deleteResponse = $this->sdk->manageLiveStream->deleteLiveStream($streamId);
        $this->assertNotNull($deleteResponse);
        $this->assertNotNull($deleteResponse->liveStreamDeleteResponse);
        $this->assertTrue($deleteResponse->liveStreamDeleteResponse->success ?? true);
        echo "\nDeleted Live Stream Response:\n";
        echo json_encode($deleteResponse->liveStreamDeleteResponse, JSON_PRETTY_PRINT) . "\n";
    }

    public function testStartLiveStream(): void
    {
        $playbackSettings = new Components\PlaybackSettings(
            Components\PlaybackSettingsAccessPolicy::Public
        );
        $inputMediaSettings = new Components\InputMediaSettings(
            null, // metadata
            Components\CreateLiveStreamRequestMaxResolution::OneThousandAndEightyp,
            60, // reconnectWindow
            Components\MediaPolicy::Public
        );
        $request = new Components\CreateLiveStreamRequest(
            $playbackSettings,
            $inputMediaSettings
        );
        $response = $this->sdk->startLiveStream->createNewStream($request);
        $this->assertNotNull($response);
        $this->assertNotNull($response->liveStreamResponseDTO);
        $this->assertTrue($response->liveStreamResponseDTO->success);
        $this->assertNotNull($response->liveStreamResponseDTO->data);
        $this->assertNotNull($response->liveStreamResponseDTO->data->streamId);
        echo "\nStarted Live Stream:\n";
        echo json_encode($response->liveStreamResponseDTO->data, JSON_PRETTY_PRINT) . "\n";
    }

    public function testCreateSimulcastOfStream(): void
    {
        // You may need to set a valid streamId here, or use one from a previous test
        $streamId = $this->testStreamId;
        if (empty($streamId)) {
            $this->markTestSkipped('No stream ID available for simulcast test');
            return;
        }
        // Use a placeholder RTMP URL and stream key for the simulcast target
        $simulcastRequest = new Components\SimulcastRequest(
            url: 'rtmp://a.rtmp.youtube.com/live2',
            streamKey: 'your-youtube-stream-key' // Replace with a real key for a real test
        );
        $response = $this->sdk->simulcastStream->createSimulcastOfStream($streamId, $simulcastRequest);
        $this->assertNotNull($response);
        $this->assertNotNull($response->simulcastResponse);
        $this->assertNotNull($response->simulcastResponse->data);
        $this->assertNotNull($response->simulcastResponse->data->simulcastId);
        echo "\nCreated Simulcast:\n";
        echo json_encode($response->simulcastResponse->data, JSON_PRETTY_PRINT) . "\n";
    }

    public function testCreateSimulcastOfStreamWithInvalidInput(): void
    {
        // Use an invalid stream ID
        $streamId = 'invalid-stream-id';
        // Use an invalid RTMP URL and stream key
        $simulcastRequest = new Components\SimulcastRequest(
            url: 'invalid-url',
            streamKey: '' // Empty stream key
        );
        try {
            $response = $this->sdk->simulcastStream->createSimulcastOfStream($streamId, $simulcastRequest);
            // If no exception, fail the test
            $this->fail('Expected an exception for invalid input, but got a response: ' . json_encode($response));
        } catch (\Exception $e) {
            echo "\nCaught expected exception for invalid input:\n";
            echo $e->getMessage() . "\n";
            $this->assertTrue(true);
        }
    }

    public function testUpdateSpecificSimulcast(): void
    {
        // First create a live stream
        $playbackSettings = new Components\PlaybackSettings(
            Components\PlaybackSettingsAccessPolicy::Public
        );
        $inputMediaSettings = new Components\InputMediaSettings(
            null, // metadata
            Components\CreateLiveStreamRequestMaxResolution::OneThousandAndEightyp,
            60, // reconnectWindow
            Components\MediaPolicy::Public
        );
        $request = new Components\CreateLiveStreamRequest(
            $playbackSettings,
            $inputMediaSettings
        );
        $createResponse = $this->sdk->startLiveStream->createNewStream($request);
        $this->assertNotNull($createResponse);
        $this->assertNotNull($createResponse->liveStreamResponseDTO);
        $streamId = $createResponse->liveStreamResponseDTO->data->streamId;

        // Create a simulcast
        $simulcastRequest = new Components\SimulcastRequest(
            url: 'rtmp://a.rtmp.youtube.com/live2',
            streamKey: 'test-stream-key'
        );
        $simulcastResponse = $this->sdk->simulcastStream->createSimulcastOfStream($streamId, $simulcastRequest);
        $this->assertNotNull($simulcastResponse);
        $this->assertNotNull($simulcastResponse->simulcastResponse);
        $simulcastId = $simulcastResponse->simulcastResponse->data->simulcastId;

        // Update the simulcast (only isEnabled and/or metadata can be updated)
        $updateRequest = new Components\SimulcastUpdateRequest(
            false // Disable the simulcast
        );
        $updateResponse = $this->sdk->simulcastStream->updateSpecificSimulcastOfStream(
            $streamId,
            $simulcastId,
            $updateRequest
        );
        $this->assertNotNull($updateResponse);
        $this->assertNotNull($updateResponse->simulcastUpdateResponse);
        $this->assertTrue($updateResponse->simulcastUpdateResponse->success ?? true);

        // Clean up - delete the simulcast and stream
        $this->sdk->simulcastStream->deleteSimulcastOfStream($streamId, $simulcastId);
        $this->sdk->manageLiveStream->deleteLiveStream($streamId);
    }

    public function testDeleteSimulcast(): void
    {
        // First create a live stream
        $playbackSettings = new Components\PlaybackSettings(
            Components\PlaybackSettingsAccessPolicy::Public
        );
        $inputMediaSettings = new Components\InputMediaSettings(
            null, // metadata
            Components\CreateLiveStreamRequestMaxResolution::OneThousandAndEightyp,
            60, // reconnectWindow
            Components\MediaPolicy::Public
        );
        $request = new Components\CreateLiveStreamRequest(
            $playbackSettings,
            $inputMediaSettings
        );
        $createResponse = $this->sdk->startLiveStream->createNewStream($request);
        $this->assertNotNull($createResponse);
        $this->assertNotNull($createResponse->liveStreamResponseDTO);
        $streamId = $createResponse->liveStreamResponseDTO->data->streamId;

        // Create a simulcast
        $simulcastRequest = new Components\SimulcastRequest(
            'rtmp://a.rtmp.youtube.com/live2',
            'test-stream-key'
        );
        $simulcastResponse = $this->sdk->simulcastStream->createSimulcastOfStream($streamId, $simulcastRequest);
        $this->assertNotNull($simulcastResponse);
        $this->assertNotNull($simulcastResponse->simulcastResponse);
        $this->assertNotNull($simulcastResponse->simulcastResponse->data);
        $this->assertNotNull($simulcastResponse->simulcastResponse->data->simulcastId);
        $simulcastId = $simulcastResponse->simulcastResponse->data->simulcastId;

        // Delete the simulcast
        try {
            $deleteResponse = $this->sdk->simulcastStream->deleteSimulcastOfStream($streamId, $simulcastId);
            $this->assertNotNull($deleteResponse);
            $this->assertNotNull($deleteResponse->simulcastdeleteResponse);
            $this->assertTrue($deleteResponse->simulcastdeleteResponse->success ?? true);
        } catch (\Exception $e) {
            echo "\nError deleting simulcast:\n";
            echo $e->getMessage() . "\n";
            throw $e;
        }

        // Clean up - delete the stream
        $this->sdk->manageLiveStream->deleteLiveStream($streamId);
    }
} 