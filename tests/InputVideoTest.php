<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use FastPix\Sdk\FastPix;
use FastPix\Sdk\Models\Components\CreateMediaRequest;
use FastPix\Sdk\Models\Components\CreateMediaRequestAccessPolicy;
use FastPix\Sdk\Models\Operations\GetVideoRequest;
use FastPix\Sdk\Models\Operations\ListVideosRequest;
use FastPix\Sdk\Models\Operations\UpdateVideoRequest;
use FastPix\Sdk\Models\Operations\DeleteMediaRequest;
use FastPix\Sdk\Models\Components\VideoInput;
use FastPix\Sdk\Models\Components\Security;
use Dotenv\Dotenv;
use FastPix\Sdk\Models\Components\Summary;
use FastPix\Sdk\Models\Components\Moderation;
use FastPix\Sdk\Models\Components\CreateMediaRequestType;
use FastPix\Sdk\Models\Components\CreateMediaRequestMaxResolution;
use FastPix\Sdk\Models\Operations\DirectUploadVideoMediaRequest;
use FastPix\Sdk\Models\Operations\PushMediaSettings;
use FastPix\Sdk\Models\Operations\DirectUploadVideoMediaAccessPolicy;

class InputVideoTest extends TestCase
{
    private FastPix $sdk;
    private string $testVideoId;

    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Initialize SDK with username and password
        $this->sdk = FastPix::builder()
            ->setSecurity(
                new Security(
                    username: $_ENV['FASTPIX_USERNAME'],
                    password: $_ENV['FASTPIX_PASSWORD'],
                )
            )
            ->setServerUrl('https://v1.fastpix.io')
            ->build();
        
        // Create a test video for use in other tests
        $this->testVideoId = $this->createTestVideo();
    }

    protected function tearDown(): void
    {
      
    }

    private function createTestVideo(): string
    {
        $request = new CreateMediaRequest(
            [
                new VideoInput(
                    type: 'video',
                    url: 'https://static.fastpix.io/sample.mp4',
                ),
            ],
            CreateMediaRequestAccessPolicy::Public,
            null, // metadata
            null, // subtitle
            null, // mp4Support
            null, // sourceAccess
            new Summary(generate: true, summaryLength: 100), // summary
            null, // chapters
            null, // namedEntities
            new Moderation(CreateMediaRequestType::Video), // moderation
            null, // accessRestrictions
            false, // optimizeAudio
            CreateMediaRequestMaxResolution::OneThousandAndEightyp // maxResolution
        );

        // Log the request details
        echo "[createTestVideo] Request Payload: " . json_encode($request) . PHP_EOL;

        try {
            $response = $this->sdk->inputVideo->createMedia($request);
            
            // Log the response for debugging
            echo "[createTestVideo] Response: "; var_dump($response);
            echo "[createTestVideo] object: "; var_dump($response->object);
            echo "[createTestVideo] success: "; var_dump($response->object->success ?? null);
            echo "[createTestVideo] data: "; var_dump($response->object->data ?? null);
            echo "[createTestVideo] data->id: "; var_dump($response->object->data->id ?? null);

            $this->assertNotNull($response->object);
            $this->assertNotNull($response->object->data);
            $this->assertNotNull($response->object->data->id);
            
            return $response->object->data->id;
        } catch (\Exception $e) {
            $this->fail('[createTestVideo] Exception caught: ' . $e->getMessage());
        }
    }

    public function testCreateVideo(): void
    {
        $request = new CreateMediaRequest(
            [
                new VideoInput(
                    type: 'video',
                    url: 'https://example.com/test-create.mp4',
                ),
            ],
            CreateMediaRequestAccessPolicy::Public
        );

        try {
            $response = $this->sdk->inputVideo->createMedia($request);
            
            // Log the response for debugging
            var_dump($response);
            
            $this->assertNotNull($response->object);
            $this->assertTrue($response->object->success);
            $this->assertNotNull($response->object->data);
            $this->assertNotNull($response->object->data->id);
            $this->assertEquals('Created', $response->object->data->status);
            $this->assertNotNull($response->object->data->playbackIds);
            $this->assertNotEmpty($response->object->data->playbackIds);
        } catch (\Exception $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        }
    }

    public function testDirectUploadVideoMedia(): void
    {
        // Example test for directUploadVideoMedia
        $request = new DirectUploadVideoMediaRequest(
            corsOrigin: '*',
            pushMediaSettings: new PushMediaSettings(
                accessPolicy: DirectUploadVideoMediaAccessPolicy::Public
            )
        );

        try {
            $response = $this->sdk->inputVideo->directUploadVideoMedia($request);
            
            // Log the response for debugging
            var_dump($response);
            
            $this->assertNotNull($response->object);
            $this->assertTrue($response->object->success);
            $this->assertNotNull($response->object->data);
            $this->assertEquals('waiting', $response->object->data->status);
            $this->assertNotNull($response->object->data->url);
            $this->assertNotEmpty($response->object->data->url);
        } catch (\Exception $e) {
            $this->fail('Exception caught: ' . $e->getMessage());
        }
    }
} 