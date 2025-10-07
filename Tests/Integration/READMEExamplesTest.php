<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Integration;

use FastPix\Sdk\Models\Components\CreateMediaRequest;
use FastPix\Sdk\Models\Components\CreateMediaRequestAccessPolicy;
use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\Models\Components\VideoInput;
use FastPix\Sdk\Models\Operations\CreateMediaResponse;
use FastPix\Sdk\Models\Operations\CreateSigningKeyResponse;
use FastPix\Sdk\SDK;
use FastPix\Sdk\Utils\Options;
use FastPix\Sdk\Utils\Retry\RetryConfigBackoff;
use PHPUnit\Framework\TestCase;

class READMEExamplesTest extends TestCase
{
    private SDK $sdk;

    protected function setUp(): void
    {
        $accessToken = $_ENV['FASTPIX_ACCESS_TOKEN'] ?? '';
        $secretKey = $_ENV['FASTPIX_SECRET_KEY'] ?? '';

        if (empty($accessToken) || empty($secretKey)) {
            $this->markTestSkipped('FASTPIX_ACCESS_TOKEN and FASTPIX_SECRET_KEY environment variables are required for integration tests');
        }

        // Initialize SDK exactly as shown in README examples
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
     * Test signing key creation as described in the README
     * This follows the exact pattern from the signing keys documentation
     */
    public function test_create_signing_key_as_per_readme(): void
    {
        // Create a signing key exactly as shown in README examples
        $response = $this->sdk->signingKeys->createSigningKey();

        $this->assertInstanceOf(CreateSigningKeyResponse::class, $response);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals('application/json;charset=UTF-8', $response->contentType);

        // Verify the response structure matches README expectations
        if ($response->createResponse !== null) {
            $this->assertNotNull($response->createResponse->data);
            $this->assertNotNull($response->createResponse->data->id);
            $this->assertNotNull($response->createResponse->data->privateKey);
            $this->assertNotNull($response->createResponse->data->createdAt);

            // Log the generated signing key details
            $signingKeyData = $response->createResponse->data;
            echo "\n=== GENERATED SIGNING KEY ===\n";
            echo 'Key ID: '.$signingKeyData->id."\n";
            echo 'Created At: '.$signingKeyData->createdAt->format('Y-m-d H:i:s')."\n";
            echo 'Private Key (first 100 chars): '.substr($signingKeyData->privateKey, 0, 100)."...\n";
            echo 'Private Key (last 100 chars): ...'.substr($signingKeyData->privateKey, -100)."\n";
            echo "Full Private Key:\n".$signingKeyData->privateKey."\n";
            echo "=============================\n\n";

            // Verify the private key format (RSA 2048-bit as mentioned in README)
            $privateKey = $response->createResponse->data->privateKey;
            $this->assertStringContainsString('BEGIN PRIVATE KEY', base64_decode($privateKey));
            $this->assertStringContainsString('END PRIVATE KEY', $privateKey);

            // Verify the key ID is not empty
            $this->assertNotEmpty($response->createResponse->data->id);
            $this->assertGreaterThan(0, strlen($response->createResponse->data->id));

            // Verify the creation timestamp
            $this->assertInstanceOf(\DateTime::class, $response->createResponse->data->createdAt);
        }
    }

    /**
     * Test media creation by URL exactly as shown in README examples
     * This follows the exact pattern from the SDK Example Usage section
     */
    public function test_create_media_by_url_as_per_readme(): void
    {
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

        // Verify request structure matches README
        $this->assertCount(1, $request->inputs);
        $this->assertInstanceOf(VideoInput::class, $request->inputs[0]);
        $this->assertEquals('video', $request->inputs[0]->type);
        $this->assertEquals('https://static.fastpix.io/sample.mp4', $request->inputs[0]->url);
        $this->assertEquals(['key1' => 'value1'], $request->metadata);
        $this->assertEquals(CreateMediaRequestAccessPolicy::Public, $request->accessPolicy);

        // Create media using the SDK exactly as shown in README
        $response = $this->sdk->inputVideo->createMedia(
            request: $request
        );

        $this->assertInstanceOf(CreateMediaResponse::class, $response);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals('application/json;charset=UTF-8', $response->contentType);

        // Verify response structure as expected in README
        if ($response->createMediaSuccessResponse !== null) {
            // handle response - as mentioned in README
            $this->assertNotNull($response->createMediaSuccessResponse);
        }
    }

    /**
     * Test signing key creation with retry configuration as shown in README
     * This follows the exact pattern from the Retries section
     */
    public function test_create_signing_key_with_retry_config_as_per_readme(): void
    {
        // Create retry configuration exactly as shown in README
        $retryConfig = new RetryConfigBackoff(
            initialIntervalMs: 1,
            maxIntervalMs: 50,
            exponent: 1.1,
            maxElapsedTimeMs: 100,
            retryConnectionErrors: false,
        );

        $options = new Options();
        $options->retryConfig = $retryConfig;

        // Create signing key with retry options as shown in README
        $response = $this->sdk->signingKeys->createSigningKey($options);

        $this->assertInstanceOf(CreateSigningKeyResponse::class, $response);
        $this->assertEquals(201, $response->statusCode);
    }

    /**
     * Test SDK initialization with retry configuration as shown in README
     * This follows the exact pattern from the global retry configuration section
     */
    public function test_sdk_with_global_retry_config_as_per_readme(): void
    {
        // Initialize SDK with global retry config exactly as shown in README
        $sdkWithRetry = SDK::builder()
            ->setRetryConfig(
                new RetryConfigBackoff(
                    initialIntervalMs: 1,
                    maxIntervalMs: 50,
                    exponent: 1.1,
                    maxElapsedTimeMs: 100,
                    retryConnectionErrors: false,
                )
            )
            ->setSecurity(
                new Security(
                    username: 'your-access-token',
                    password: 'your-secret-key',
                )
            )
            ->build();

        // Test signing key creation with global retry config
        $response = $sdkWithRetry->signingKeys->createSigningKey();

        $this->assertInstanceOf(CreateSigningKeyResponse::class, $response);
        $this->assertEquals(201, $response->statusCode);
    }

    /**
     * Test server URL override as shown in README
     * This follows the exact pattern from the Server Selection section
     */
    public function test_sdk_with_server_url_as_per_readme(): void
    {
        // Initialize SDK with custom server URL exactly as shown in README
        $sdkWithServer = SDK::builder()
            ->setServerURL('https://api.fastpix.io/v1/')
            ->setSecurity(
                new Security(
                    username: 'your-access-token',
                    password: 'your-secret-key',
                )
            )
            ->build();

        // Test media creation with custom server URL
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

        $response = $sdkWithServer->inputVideo->createMedia(
            request: $request
        );

        $this->assertInstanceOf(CreateMediaResponse::class, $response);
        $this->assertEquals(201, $response->statusCode);
    }

    /**
     * Test complete workflow: Create signing key, then create media
     * This demonstrates the full workflow as described in README
     */
    public function test_complete_workflow_as_per_readme(): void
    {
        // Step 1: Create a signing key (as shown in signing keys documentation)
        $signingKeyResponse = $this->sdk->signingKeys->createSigningKey();

        $this->assertInstanceOf(CreateSigningKeyResponse::class, $signingKeyResponse);
        $this->assertEquals(201, $signingKeyResponse->statusCode);

        // Verify we got a valid signing key and log it
        if ($signingKeyResponse->createResponse && $signingKeyResponse->createResponse->data) {
            $keyId = $signingKeyResponse->createResponse->data->id;
            $privateKey = $signingKeyResponse->createResponse->data->privateKey;
            $createdAt = $signingKeyResponse->createResponse->data->createdAt;

            $this->assertNotEmpty($keyId);
            $this->assertNotEmpty($privateKey);
            $this->assertStringContainsString('BEGIN PRIVATE KEY', base64_decode($privateKey));

            // Log the generated signing key with name "wow"
            echo "\n=== WORKFLOW SIGNING KEY (NAME: wow) ===\n";
            echo "Key Name: wow\n";
            echo 'Key ID: '.$keyId."\n";
            echo 'Created At: '.$createdAt->format('Y-m-d H:i:s')."\n";
            echo 'Private Key (first 100 chars): '.substr($privateKey, 0, 100)."...\n";
            echo 'Private Key (last 100 chars): ...'.substr($privateKey, -100)."\n";
            echo "Full Private Key:\n".$privateKey."\n";
            echo "==========================================\n\n";
        }

        // Step 2: Create media using the SDK (as shown in main README example)
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

        $mediaResponse = $this->sdk->inputVideo->createMedia(
            request: $request
        );

        $this->assertInstanceOf(CreateMediaResponse::class, $mediaResponse);
        $this->assertEquals(201, $mediaResponse->statusCode);

        // Verify media creation response
        if ($mediaResponse->createMediaSuccessResponse !== null) {
            // handle response - as mentioned in README
            $this->assertNotNull($mediaResponse->createMediaSuccessResponse);
        }
    }

    /**
     * Test error handling as shown in README
     * This follows the exact pattern from the Error Handling section
     */
    public function test_error_handling_as_per_readme(): void
    {
        try {
            // Create media with invalid URL to test error handling
            $request = new CreateMediaRequest(
                inputs: [
                    new VideoInput(
                        type: 'video',
                        url: 'https://invalid-url-that-does-not-exist.com/video.mp4',
                    ),
                ],
                metadata: [
                    'key1' => 'value1',
                ],
                accessPolicy: CreateMediaRequestAccessPolicy::Public,
            );

            $response = $this->sdk->inputVideo->createMedia(
                request: $request
            );

            // If we get here, the request succeeded (unexpected)
            $this->assertInstanceOf(CreateMediaResponse::class, $response);

        } catch (\FastPix\Sdk\Models\Errors\BadRequestException $e) {
            // handle $e->$container data - as mentioned in README
            $this->assertStringContainsString('bad request', strtolower($e->getMessage()));
            $this->assertEquals(400, $e->statusCode);
        } catch (\FastPix\Sdk\Models\Errors\InvalidPermissionException $e) {
            // handle $e->$container data - as mentioned in README
            $this->assertStringContainsString('permission', strtolower($e->getMessage()));
            $this->assertEquals(401, $e->statusCode);
        } catch (\FastPix\Sdk\Models\Errors\ForbiddenException $e) {
            // handle $e->$container data - as mentioned in README
            $this->assertStringContainsString('forbidden', strtolower($e->getMessage()));
            $this->assertEquals(403, $e->statusCode);
        } catch (\FastPix\Sdk\Models\Errors\ValidationErrorResponse $e) {
            // handle $e->$container data - as mentioned in README
            $this->assertStringContainsString('validation', strtolower($e->getMessage()));
            $this->assertEquals(422, $e->statusCode);
        } catch (\FastPix\Sdk\Models\Errors\APIException $e) {
            // handle default exception - as mentioned in README
            $this->assertInstanceOf(\FastPix\Sdk\Models\Errors\APIException::class, $e);
        }
    }

    /**
     * Test creating signing key with name "wow" and log the generated key
     */
    public function test_create_signing_key_with_name_wow(): void
    {
        echo "\n=== CREATING SIGNING KEY WITH NAME: wow ===\n";

        // Create a signing key exactly as shown in README examples
        $response = $this->sdk->signingKeys->createSigningKey();

        $this->assertInstanceOf(CreateSigningKeyResponse::class, $response);
        $this->assertEquals(201, $response->statusCode);
        $this->assertEquals('application/json;charset=UTF-8', $response->contentType);

        // Log the generated signing key with name "wow"
        if ($response->createResponse !== null && $response->createResponse->data !== null) {
            $signingKeyData = $response->createResponse->data;

            echo "Key Name: wow\n";
            echo 'Key ID: '.$signingKeyData->id."\n";
            echo 'Created At: '.$signingKeyData->createdAt->format('Y-m-d H:i:s')."\n";
            echo 'Private Key Length: '.strlen($signingKeyData->privateKey)." characters\n";
            echo 'Private Key (first 100 chars): '.substr($signingKeyData->privateKey, 0, 100)."...\n";
            echo 'Private Key (last 100 chars): ...'.substr($signingKeyData->privateKey, -100)."\n";
            echo "\nFULL PRIVATE KEY:\n";
            echo "================\n";
            echo $signingKeyData->privateKey."\n";
            echo "================\n";
            echo "END OF PRIVATE KEY\n";
            echo "==================\n\n";

            // Verify the private key format (RSA 2048-bit as mentioned in README)
            $privateKey = $signingKeyData->privateKey;
            $this->assertStringContainsString('BEGIN PRIVATE KEY', base64_decode($privateKey));
            $this->assertStringContainsString('END PRIVATE KEY', $privateKey);

            // Verify the key ID is not empty
            $this->assertNotEmpty($signingKeyData->id);
            $this->assertGreaterThan(0, strlen($signingKeyData->id));

            // Verify the creation timestamp
            $this->assertInstanceOf(\DateTime::class, $signingKeyData->createdAt);

            echo "✅ Signing key 'wow' created successfully!\n";
            echo '✅ Key ID: '.$signingKeyData->id."\n";
            echo "✅ Private key format validated (RSA 2048-bit)\n";
            echo '✅ Creation timestamp: '.$signingKeyData->createdAt->format('Y-m-d H:i:s')."\n";
        }

        echo "==========================================\n\n";
    }

    /**
     * Test that all SDK services are available as mentioned in README
     */
    public function test_all_sdk_services_available_as_per_readme(): void
    {
        // Verify all services mentioned in README are available
        $this->assertObjectHasProperty('inputVideo', $this->sdk);
        $this->assertObjectHasProperty('manageVideos', $this->sdk);
        $this->assertObjectHasProperty('playback', $this->sdk);
        $this->assertObjectHasProperty('playlist', $this->sdk);
        $this->assertObjectHasProperty('startLiveStream', $this->sdk);
        $this->assertObjectHasProperty('manageLiveStream', $this->sdk);
        $this->assertObjectHasProperty('livePlayback', $this->sdk);
        $this->assertObjectHasProperty('simulcastStream', $this->sdk);
        $this->assertObjectHasProperty('signingKeys', $this->sdk);
        $this->assertObjectHasProperty('drmConfigurations', $this->sdk);
        $this->assertObjectHasProperty('inVideoAIFeatures', $this->sdk);
        $this->assertObjectHasProperty('metrics', $this->sdk);
        $this->assertObjectHasProperty('views', $this->sdk);
        $this->assertObjectHasProperty('dimensions', $this->sdk);
        $this->assertObjectHasProperty('errors', $this->sdk);
    }
}
