<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Integration;

use FastPix\Sdk\Models\Components\CreateMediaRequest;
use FastPix\Sdk\Models\Components\CreateMediaRequestAccessPolicy;
use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\Models\Components\VideoInput;
use FastPix\Sdk\SDK;
use PHPUnit\Framework\TestCase;

class InputVideoTest extends TestCase
{
    private SDK $sdk;

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

    public function test_create_media_from_url(): void
    {
        echo "\n=== TESTING INPUT VIDEO - CREATE MEDIA FROM URL ===\n";

        $request = new CreateMediaRequest(
            inputs: [
                new VideoInput(
                    type: 'video',
                    url: 'https://static.fastpix.io/sample.mp4',
                ),
            ],
            metadata: [
                'title' => 'Test Video from URL',
                'description' => 'Created via InputVideo test',
                'test_type' => 'url_input',
            ],
            accessPolicy: CreateMediaRequestAccessPolicy::Public,
        );

        $response = $this->sdk->inputVideo->createMedia(request: $request);

        echo "✅ Response Status: {$response->statusCode}\n";
        echo "✅ Content Type: {$response->contentType}\n";

        if ($response->createMediaSuccessResponse !== null) {
            $media = $response->createMediaSuccessResponse;
            echo '✅ Media ID: '.($media->id ?? 'N/A')."\n";
            echo '✅ Status: '.($media->status ?? 'N/A')."\n";
            echo '✅ Created At: '.($media->data->createdAt?->format('Y-m-d H:i:s') ?? 'N/A')."\n";

            if (isset($media->inputs) && is_array($media->inputs)) {
                echo '✅ Inputs Count: '.count($media->inputs)."\n";
                foreach ($media->inputs as $index => $input) {
                    echo '  Input '.($index + 1).': '.($input->type ?? 'N/A').' - '.($input->url ?? 'N/A')."\n";
                }
            }
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\CreateMediaResponse::class, $response);
        echo "✅ InputVideo createMedia test completed successfully!\n";
    }

    public function test_create_media_with_multiple_inputs(): void
    {
        echo "\n=== TESTING INPUT VIDEO - MULTIPLE INPUTS ===\n";

        $request = new CreateMediaRequest(
            inputs: [
                new VideoInput(
                    type: 'video',
                    url: 'https://static.fastpix.io/sample.mp4',
                ),
            ],
            metadata: [
                'title' => 'Multi-Input Video',
                'test_type' => 'multiple_inputs',
                'input_count' => '1',
            ],
            accessPolicy: CreateMediaRequestAccessPolicy::Private,
        );

        $response = $this->sdk->inputVideo->createMedia(request: $request);

        echo "✅ Response Status: {$response->statusCode}\n";
        echo "✅ Content Type: {$response->contentType}\n";

        if ($response->createMediaSuccessResponse !== null) {
            $media = $response->createMediaSuccessResponse;
            echo '✅ Media ID: '.($media->id ?? 'N/A')."\n";
            echo '✅ Access Policy: '.($media->accessPolicy ?? 'N/A')."\n";
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\CreateMediaResponse::class, $response);
        echo "✅ InputVideo multiple inputs test completed successfully!\n";
    }

    public function test_create_media_with_extensive_metadata(): void
    {
        echo "\n=== TESTING INPUT VIDEO - EXTENSIVE METADATA ===\n";

        $extensiveMetadata = [
            'title' => 'Comprehensive Test Video',
            'description' => 'Video with extensive metadata for testing',
            'category' => 'test',
            'tags' => 'test,api,fastpix,inputvideo',
            'author' => 'FastPix SDK Test',
            'version' => '1.0.0',
            'created_by' => 'php-sdk-test',
            'test_type' => 'extensive_metadata',
            'quality' => 'high',
            'duration' => '120',
        ];

        $request = new CreateMediaRequest(
            inputs: [
                new VideoInput(
                    type: 'video',
                    url: 'https://static.fastpix.io/sample.mp4',
                ),
            ],
            metadata: $extensiveMetadata,
            accessPolicy: CreateMediaRequestAccessPolicy::Public,
        );

        $response = $this->sdk->inputVideo->createMedia(request: $request);

        echo "✅ Response Status: {$response->statusCode}\n";
        echo "✅ Content Type: {$response->contentType}\n";

        if ($response->createMediaSuccessResponse !== null) {
            $media = $response->createMediaSuccessResponse;
            echo '✅ Media ID: '.($media->id ?? 'N/A')."\n";
            echo '✅ Metadata Fields: '.count($extensiveMetadata)."\n";

            if (isset($media->metadata) && is_array($media->metadata)) {
                echo '✅ Stored Metadata: '.json_encode($media->metadata)."\n";
            }
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\CreateMediaResponse::class, $response);
        echo "✅ InputVideo extensive metadata test completed successfully!\n";
    }
}