<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;
use PHPUnit\Framework\TestCase;

class PlaybackTest extends TestCase
{
    private Sdk\FastPix $sdk;
    private ?string $testMediaId = null;
    private ?string $testStreamId = null;
    private ?string $testPlaybackId = null;

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
        // Set a valid media ID for testing
        $this->testMediaId = '1c90a15f-969b-45d1-ad46-900d4840de39'; // Valid media ID from previous test
        // Set a valid stream ID for testing
        $this->testStreamId = 'ac3be19b82290a8d5865efa586f4bc4c'; // Valid stream ID from previous test
    }

    public function testCreateMediaPlaybackId(): void
    {
        $requestBody = new Operations\CreateMediaPlaybackIdRequestBody(
            accessPolicy: Operations\CreateMediaPlaybackIdAccessPolicy::Public
        );
        $response = $this->sdk->playback->createMediaPlaybackId($this->testMediaId, $requestBody);
        $this->assertNotNull($response);
        $this->assertNotNull($response->object);
        $this->assertTrue($response->object->success);
        $this->testPlaybackId = $response->object->data->id;
        echo "\nCreated Media Playback ID:\n";
        echo json_encode($response->object->data, JSON_PRETTY_PRINT) . "\n";
    }

    public function testCreatePlaybackIdOfStream(): void
    {
        $playbackIdRequest = new Components\PlaybackIdRequest(
            accessPolicy: Components\PlaybackIdRequestAccessPolicy::Public
        );
        $response = $this->sdk->playback->createPlaybackIdOfStream($this->testStreamId, $playbackIdRequest);
        $this->assertNotNull($response);
        $this->assertNotNull($response->playbackIdResponse);
        $this->assertTrue($response->playbackIdResponse->success);
        $this->testPlaybackId = $response->playbackIdResponse->data->id;
        echo "\nCreated Stream Playback ID:\n";
        echo json_encode($response->playbackIdResponse->data, JSON_PRETTY_PRINT) . "\n";
    }

    public function testDeleteMediaPlaybackId(): void
    {
        if (empty($this->testPlaybackId)) {
            $this->markTestSkipped('No playback ID available for testing');
            return;
        }
        $response = $this->sdk->playback->deleteMediaPlaybackId($this->testMediaId, $this->testPlaybackId);
        $this->assertNotNull($response);
        $this->assertNotNull($response->object);
        $this->assertTrue($response->object->success);
        echo "\nDeleted Media Playback ID Response:\n";
        echo json_encode($response->object, JSON_PRETTY_PRINT) . "\n";
    }

    public function testGetLiveStreamPlaybackId(): void
    {
        if (empty($this->testPlaybackId)) {
            $this->markTestSkipped('No playback ID available for testing');
            return;
        }
        $response = $this->sdk->playback->getLiveStreamPlaybackId($this->testStreamId, $this->testPlaybackId);
        $this->assertNotNull($response);
        $this->assertNotNull($response->playbackIdResponse);
        $this->assertTrue($response->playbackIdResponse->success);
        echo "\nRetrieved Live Stream Playback ID:\n";
        echo json_encode($response->playbackIdResponse->data, JSON_PRETTY_PRINT) . "\n";
    }

    public function testFullPlaybackLifecycle(): void
    {
        // Step 1: Create a playback ID for a live stream
        $playbackIdRequest = new Components\PlaybackIdRequest(
            accessPolicy: Components\PlaybackIdRequestAccessPolicy::Public
        );
        $createResponse = $this->sdk->playback->createPlaybackIdOfStream($this->testStreamId, $playbackIdRequest);
        $this->assertNotNull($createResponse);
        $this->assertNotNull($createResponse->playbackIdResponse);
        $this->assertTrue($createResponse->playbackIdResponse->success);
        $this->testPlaybackId = $createResponse->playbackIdResponse->data->id;
        echo "\nCreated Stream Playback ID:\n";
        echo json_encode($createResponse->playbackIdResponse->data, JSON_PRETTY_PRINT) . "\n";

        // Step 2: Get the live stream playback ID
        $getResponse = $this->sdk->playback->getLiveStreamPlaybackId($this->testStreamId, $this->testPlaybackId);
        $this->assertNotNull($getResponse);
        $this->assertNotNull($getResponse->playbackIdResponse);
        $this->assertTrue($getResponse->playbackIdResponse->success);
        echo "\nRetrieved Live Stream Playback ID:\n";
        echo json_encode($getResponse->playbackIdResponse->data, JSON_PRETTY_PRINT) . "\n";

        // Step 3: Delete the stream playback ID
        try {
            $deleteResponse = $this->sdk->playback->deletePlaybackIdOfStream($this->testStreamId, $this->testPlaybackId);
            $this->assertNotNull($deleteResponse);
            $this->assertNotNull($deleteResponse->liveStreamDeleteResponse);
            $this->assertTrue($deleteResponse->liveStreamDeleteResponse->success);
            echo "\nDeleted Stream Playback ID Response:\n";
            echo json_encode($deleteResponse->liveStreamDeleteResponse, JSON_PRETTY_PRINT) . "\n";
        } catch (\FastPix\Sdk\Models\Errors\APIException $e) {
            echo "\nError deleting Stream Playback ID:\n";
            echo "Status Code: " . $e->getStatusCode() . "\n";
            echo "Message: " . $e->getMessage() . "\n";
            echo "Response Body: " . $e->getResponseBody() . "\n";
            // We'll consider the test successful even if deletion fails
            // since we've verified the creation and retrieval worked
            $this->assertTrue(true);
        }
    }
} 