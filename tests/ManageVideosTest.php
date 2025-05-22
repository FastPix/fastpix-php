<?php

declare(strict_types=1);

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;
use FastPix\Sdk\Models\Errors\APIException;
use FastPix\Sdk\Models\Errors\ValidationErrorResponseThrowable;
use PHPUnit\Framework\TestCase;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

class ManageVideosTest extends TestCase
{
    private Sdk\FastPix $sdk;
    private string $testMediaId;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->sdk = Sdk\FastPix::builder()
            ->setSecurity(
                new Components\Security(
                    username: $_ENV['FASTPIX_USERNAME'],
                    password: $_ENV['FASTPIX_PASSWORD'],
                )
            )
            ->setServerUrl('https://v1.fastpix.io')
            ->build();

        // Get a media ID for testing other operations
        $response = $this->sdk->manageVideos->listMedia(
            limit: 1,
            offset: 1,
            orderBy: Operations\ListMediaOrderBy::Desc
        );
        if ($response->object !== null && !empty($response->object->data)) {
            $this->testMediaId = $response->object->data[0]->id;
        }
    }

    public function testListMedia(): void
    {
        try {
            $response = $this->sdk->manageVideos->listMedia(
                limit: 20,
                offset: 1,
                orderBy: Operations\ListMediaOrderBy::Desc
            );

            $this->assertNotNull($response);
            $this->assertNotNull($response->object);
        } catch (APIException $e) {
            echo "\nAPI Error Details:\n";
            echo "Status Code: " . $e->statusCode . "\n";
            echo "Response Body: " . $e->getResponseBody() . "\n";
            throw $e;
        }
    }

    public function testGetMedia(): void
    {
        if (empty($this->testMediaId)) {
            $this->markTestSkipped('No media ID available for testing');
            return;
        }

        try {
            $response = $this->sdk->manageVideos->getMedia(
                mediaId: $this->testMediaId
            );

            $this->assertNotNull($response);
            $this->assertNotNull($response->object);
        } catch (APIException $e) {
            echo "\nAPI Error Details:\n";
            echo "Status Code: " . $e->statusCode . "\n";
            echo "Response Body: " . $e->getResponseBody() . "\n";
            throw $e;
        }
    }

    public function testRetrieveMediaInputInfo(): void
    {
        if (empty($this->testMediaId)) {
            $this->markTestSkipped('No media ID available for testing');
            return;
        }

        try {
            $response = $this->sdk->manageVideos->retrieveMediaInputInfo(
                mediaId: $this->testMediaId
            );

            $this->assertNotNull($response);
            $this->assertNotNull($response->object);
        } catch (APIException $e) {
            echo "\nAPI Error Details:\n";
            echo "Status Code: " . $e->statusCode . "\n";
            echo "Response Body: " . $e->getResponseBody() . "\n";
            throw $e;
        }
    }

    public function testUpdateMedia(): void
    {
        if (empty($this->testMediaId)) {
            $this->markTestSkipped('No media ID available for testing');
            return;
        }

        try {
            // Create metadata using UpdatedMediaMetadata class
            $metadata = new Operations\UpdatedMediaMetadata();
            $metadata->title = 'hello Title';
            $metadata->description = 'Test Description';

            $requestBody = new Operations\UpdatedMediaRequestBody(
                metadata: $metadata
            );

            // Print request body
            echo "\nUpdate Media Request:\n";
            echo "Media ID: " . $this->testMediaId . "\n";
            echo "Request Body: " . json_encode($requestBody, JSON_PRETTY_PRINT) . "\n";

            $response = $this->sdk->manageVideos->updatedMedia(
                mediaId: $this->testMediaId,
                requestBody: $requestBody
            );

            $this->assertNotNull($response);
            $this->assertNotNull($response->object);
            $this->assertTrue($response->object->success);
            $this->assertNotNull($response->object->data);

            // Print complete response details
            echo "\nUpdate Media Response:\n";
            echo "Success: " . ($response->object->success ? 'true' : 'false') . "\n";
            if ($response->object->data !== null) {
                echo "Media ID: " . $response->object->data->id . "\n";
                echo "Status: " . $response->object->data->status . "\n";
                echo "Workspace ID: " . $response->object->data->workspaceId . "\n";
                echo "Duration: " . $response->object->data->duration . "\n";
                echo "Frame Rate: " . $response->object->data->frameRate . "\n";
                echo "Aspect Ratio: " . $response->object->data->aspectRatio . "\n";
                echo "Created At: " . ($response->object->data->createdAt ? $response->object->data->createdAt->format('Y-m-d H:i:s') : 'null') . "\n";
                echo "Updated At: " . ($response->object->data->updatedAt ? $response->object->data->updatedAt->format('Y-m-d H:i:s') : 'null') . "\n";
                
                // Print metadata if available
                if ($response->object->data->metadata !== null) {
                    echo "\nMetadata:\n";
                    echo json_encode($response->object->data->metadata, JSON_PRETTY_PRINT) . "\n";
                }

                // Print max resolution if available
                if ($response->object->data->maxResolution !== null) {
                    echo "\nMax Resolution:\n";
                    echo json_encode($response->object->data->maxResolution, JSON_PRETTY_PRINT) . "\n";
                }

                // Print source resolution if available
                if ($response->object->data->sourceResolution !== null) {
                    echo "\nSource Resolution:\n";
                    echo json_encode($response->object->data->sourceResolution, JSON_PRETTY_PRINT) . "\n";
                }

                // Print playback IDs if available
                if (!empty($response->object->data->playbackIds)) {
                    echo "\nPlayback IDs:\n";
                    echo json_encode($response->object->data->playbackIds, JSON_PRETTY_PRINT) . "\n";
                }

                // Print tracks if available
                if (!empty($response->object->data->tracks)) {
                    echo "\nTracks:\n";
                    echo json_encode($response->object->data->tracks, JSON_PRETTY_PRINT) . "\n";
                }
            }

            // Get media details after update to verify metadata
            echo "\nVerifying Media Details After Update:\n";
            $mediaResponse = $this->sdk->manageVideos->getMedia(
                mediaId: $this->testMediaId
            );

            if ($mediaResponse->object !== null && $mediaResponse->object->data !== null) {
                echo "Media ID: " . $mediaResponse->object->data->id . "\n";
                if ($mediaResponse->object->data->metadata !== null) {
                    echo "\nMetadata:\n";
                    echo json_encode($mediaResponse->object->data->metadata, JSON_PRETTY_PRINT) . "\n";
                }
            }
        } catch (APIException $e) {
            echo "\nAPI Error Details:\n";
            echo "Status Code: " . $e->statusCode . "\n";
            echo "Response Body: " . $e->getResponseBody() . "\n";
            throw $e;
        }
    }

    public function testDeleteMedia(): void
    {
        if (empty($this->testMediaId)) {
            $this->markTestSkipped('No media ID available for testing');
            return;
        }

        try {
            $response = $this->sdk->manageVideos->deleteMedia(
                mediaId: $this->testMediaId
            );

            $this->assertNotNull($response);
            $this->assertNotNull($response->object);
        } catch (APIException $e) {
            echo "\nAPI Error Details:\n";
            echo "Status Code: " . $e->statusCode . "\n";
            echo "Response Body: " . $e->getResponseBody() . "\n";
            throw $e;
        }
    } 

    public function testListMediaWithInvalidCredentials(): void
    {
        $sdk = Sdk\FastPix::builder()
            ->setSecurity(
                new Components\Security(
                    username: 'invalid',
                    password: 'invalid',
                )
            )
            ->setServerUrl('https://v1.fastpix.io')
            ->build();

        $this->expectException(\Exception::class);
        
        $sdk->manageVideos->listMedia(
            limit: 20,
            offset: 1,
            orderBy: Operations\ListMediaOrderBy::Desc
        );
    }

    public function testGetMediaWithInvalidId(): void
    {
        $this->expectException(ValidationErrorResponseThrowable::class);
        
        $this->sdk->manageVideos->getMedia(
            mediaId: 'invalid-media-id'
        );
    }
} 