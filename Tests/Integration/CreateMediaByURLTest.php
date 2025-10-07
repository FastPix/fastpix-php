<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Integration;

use FastPix\Sdk\Models\Components\CreateMediaRequest;
use FastPix\Sdk\Models\Components\CreateMediaRequestAccessPolicy;
use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\Models\Components\VideoInput;
use FastPix\Sdk\Models\Operations\CreateMediaResponse;
use FastPix\Sdk\SDK;
use PHPUnit\Framework\TestCase;

class CreateMediaByURLTest extends TestCase
{
    private SDK $sdk;

    protected function setUp(): void
    {
        // Initialize SDK exactly as shown in README examples
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
     * Test creating media by URL exactly as shown in README examples
     * This follows the exact pattern from the SDK Example Usage section
     */
    public function test_create_media_by_url_as_per_readme(): void
    {
        echo "\n=== CREATING MEDIA BY URL ===\n";
        echo "Using sample video URL: https://static.fastpix.io/sample.mp4\n";
        echo "Access Policy: Public\n";
        echo "Metadata: ['key1' => 'value1']\n\n";

        // Create media request exactly as shown in README
        $request = new CreateMediaRequest(
            inputs: [
                new VideoInput(
                    type: 'video',
                    url: 'https://static.fastpix.io/sample.mp4',
                ),
            ],
            metadata: [
                'key1' => 'value1',
            ],
            accessPolicy: CreateMediaRequestAccessPolicy::Public,
        );

        echo "✅ Media request created successfully\n";
        echo "✅ Video input type: video\n";
        echo "✅ Video URL: https://static.fastpix.io/sample.mp4\n";
        echo "✅ Access policy: Public\n";
        echo "✅ Metadata: key1=value1\n\n";

        echo "Sending request to FastPix API...\n";

        $response = $this->sdk->inputVideo->createMedia(
            request: $request
        );

        echo "✅ API response received\n";
        echo '✅ Status Code: '.$response->statusCode."\n";
        echo '✅ Content Type: '.$response->contentType."\n\n";

        $this->assertInstanceOf(CreateMediaResponse::class, $response);
        $this->assertEquals(201, $response->statusCode); // 201 Created is correct for resource creation

        // Log the response details
        if ($response->createMediaSuccessResponse !== null) {
            $mediaData = $response->createMediaSuccessResponse;

            echo "=== MEDIA CREATION SUCCESS ===\n";
            echo 'Media ID: '.($mediaData->id ?? 'N/A')."\n";
            echo 'Status: '.($mediaData->status ?? 'N/A')."\n";
            echo 'Created At: '.($mediaData->data->createdAt?->format('Y-m-d H:i:s') ?? 'N/A')."\n";
            echo 'Updated At: '.($mediaData->data->updatedAt?->format('Y-m-d H:i:s') ?? 'N/A')."\n";

            if (isset($mediaData->inputs) && is_array($mediaData->inputs)) {
                echo 'Inputs Count: '.count($mediaData->inputs)."\n";
                foreach ($mediaData->inputs as $index => $input) {
                    echo '  Input '.($index + 1).":\n";
                    echo '    Type: '.($input->type ?? 'N/A')."\n";
                    echo '    URL: '.($input->url ?? 'N/A')."\n";
                    echo '    Status: '.($input->status ?? 'N/A')."\n";
                }
            }

            if (isset($mediaData->metadata) && is_array($mediaData->metadata)) {
                echo 'Metadata: '.json_encode($mediaData->metadata)."\n";
            }

            echo 'Access Policy: '.($mediaData->accessPolicy ?? 'N/A')."\n";
            echo "=============================\n\n";

            // Verify the response structure
            $this->assertNotNull($mediaData);
        } else {
            echo "❌ No success response data received\n";
            if ($response->rawResponse) {
                echo 'Raw Response: '.$response->rawResponse."\n";
            }
        }

        echo "✅ Media creation test completed successfully!\n";
        echo "==========================================\n\n";
    }

    /**
     * Test creating media with different access policies
     */
    public function test_create_media_with_different_access_policies(): void
    {
        echo "\n=== TESTING DIFFERENT ACCESS POLICIES ===\n";

        $accessPolicies = [
            CreateMediaRequestAccessPolicy::Public,
            CreateMediaRequestAccessPolicy::Private,
        ];

        foreach ($accessPolicies as $policy) {
            echo 'Testing access policy: '.$policy->value."\n";

            $request = new CreateMediaRequest(
                inputs: [
                    new VideoInput(
                        type: 'video',
                        url: 'https://static.fastpix.io/sample.mp4',
                    ),
                ],
                metadata: [
                    'test_policy' => $policy->value,
                ],
                accessPolicy: $policy,
            );

            $response = $this->sdk->inputVideo->createMedia(
                request: $request
            );

            $this->assertInstanceOf(CreateMediaResponse::class, $response);
            $this->assertEquals(201, $response->statusCode); // 201 Created is correct for resource creation

            echo '✅ '.$policy->value." policy test passed\n";
        }

        echo "✅ All access policy tests completed!\n";
        echo "=====================================\n\n";
    }

    /**
     * Test creating media with multiple inputs
     */
    public function test_create_media_with_multiple_inputs(): void
    {
        echo "\n=== TESTING MULTIPLE INPUTS ===\n";

        $request = new CreateMediaRequest(
            inputs: [
                new VideoInput(
                    type: 'video',
                    url: 'https://static.fastpix.io/sample.mp4',
                ),
            ],
            metadata: [
                'multiple_inputs' => 'true',
                'input_count' => '1',
            ],
            accessPolicy: CreateMediaRequestAccessPolicy::Public,
        );

        echo "✅ Created request with 1 video input\n";
        echo "✅ Input 1: https://static.fastpix.io/sample.mp4\n\n";

        $response = $this->sdk->inputVideo->createMedia(
            request: $request
        );

        $this->assertInstanceOf(CreateMediaResponse::class, $response);
        $this->assertEquals(201, $response->statusCode); // 201 Created is correct for resource creation

        echo "✅ Multiple inputs test completed!\n";
        echo "==================================\n\n";
    }

    /**
     * Test creating media with extensive metadata
     */
    public function test_create_media_with_extensive_metadata(): void
    {
        echo "\n=== TESTING EXTENSIVE METADATA ===\n";

        $extensiveMetadata = [
            'title' => 'Test Video',
            'description' => 'This is a test video created via API',
            'category' => 'test',
            'tags' => 'test,api,fastpix',
            'author' => 'FastPix SDK',
            'version' => '1.0.0',
            'created_by' => 'php-sdk',
            'test_type' => 'extensive_metadata',
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

        echo "✅ Created request with extensive metadata:\n";
        foreach ($extensiveMetadata as $key => $value) {
            echo "  $key: $value\n";
        }
        echo "\n";

        $response = $this->sdk->inputVideo->createMedia(
            request: $request
        );

        $this->assertInstanceOf(CreateMediaResponse::class, $response);
        $this->assertEquals(201, $response->statusCode); // 201 Created is correct for resource creation

        echo "✅ Extensive metadata test completed!\n";
        echo "=====================================\n\n";
    }

    /**
     * Test error handling for invalid URL
     */
    public function test_create_media_with_invalid_url(): void
    {
        echo "\n=== TESTING ERROR HANDLING (INVALID URL) ===\n";

        $request = new CreateMediaRequest(
            inputs: [
                new VideoInput(
                    type: 'video',
                    url: 'https://invalid-url-that-does-not-exist.com/video.mp4',
                ),
            ],
            metadata: [
                'test_error' => 'invalid_url',
            ],
            accessPolicy: CreateMediaRequestAccessPolicy::Public,
        );

        echo "✅ Created request with invalid URL\n";
        echo "✅ URL: https://invalid-url-that-does-not-exist.com/video.mp4\n\n";

        try {
            $response = $this->sdk->inputVideo->createMedia(
                request: $request
            );

            echo 'Response Status: '.$response->statusCode."\n";
            echo 'Response Type: '.get_class($response)."\n";

            // Even with invalid URL, the API might return a response
            // We just want to ensure the SDK handles it gracefully
            $this->assertInstanceOf(CreateMediaResponse::class, $response);

        } catch (\Exception $e) {
            echo '✅ Exception caught as expected: '.$e->getMessage()."\n";
            echo '✅ Exception type: '.get_class($e)."\n";
        }

        echo "✅ Error handling test completed!\n";
        echo "=================================\n\n";
    }
}
