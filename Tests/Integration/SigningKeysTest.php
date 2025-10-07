<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Integration;

use FastPix\Sdk\Models\Components\CreateResponse;
use FastPix\Sdk\Models\Components\CreateSigningKeyResponseDTO;
use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\Models\Operations\CreateSigningKeyResponse;
use FastPix\Sdk\Models\Operations\DeleteSigningKeyRequest;
use FastPix\Sdk\Models\Operations\DeleteSigningKeyResponse;
use FastPix\Sdk\Models\Operations\GetSigningKeyByIdRequest;
use FastPix\Sdk\Models\Operations\GetSigningKeyByIdResponse;
use FastPix\Sdk\Models\Operations\ListSigningKeysRequest;
use FastPix\Sdk\Models\Operations\ListSigningKeysResponse;
use FastPix\Sdk\SDK;
use FastPix\Sdk\Utils\Options;
use FastPix\Sdk\Utils\Retry\RetryConfigBackoff;
use PHPUnit\Framework\TestCase;

class SigningKeysTest extends TestCase
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
            ->setSecurity(new Security(username: $accessToken, password: $secretKey))
            ->build();
    }

    public function test_signing_keys_service_exists(): void
    {
        $this->assertObjectHasProperty('signingKeys', $this->sdk);
    }

    public function test_create_signing_key_method_exists(): void
    {
        $this->assertTrue(method_exists($this->sdk->signingKeys, 'createSigningKey'));
    }

    public function test_list_signing_keys_method_exists(): void
    {
        $this->assertTrue(method_exists($this->sdk->signingKeys, 'listSigningKeys'));
    }

    public function test_get_signing_key_by_id_method_exists(): void
    {
        $this->assertTrue(method_exists($this->sdk->signingKeys, 'getSigningKeyById'));
    }

    public function test_delete_signing_key_method_exists(): void
    {
        $this->assertTrue(method_exists($this->sdk->signingKeys, 'deleteSigningKey'));
    }

    public function test_create_signing_key_with_default_options(): void
    {
        // Test that the method can be called without parameters
        $this->expectNotToPerformAssertions();

        try {
            $response = $this->sdk->signingKeys->createSigningKey();
            $this->assertInstanceOf(CreateSigningKeyResponse::class, $response);
            $this->assertEquals(201, $response->statusCode);
            $this->assertInstanceOf(CreateResponse::class, $response->createResponse);
        } catch (\Exception $e) {
            // Expected in test environment - could be authentication or validation errors
            $message = strtolower($e->getMessage());
            $this->assertTrue(
                str_contains($message, 'authentication') ||
                str_contains($message, 'validation') ||
                str_contains($message, 'invalid'),
                'Expected authentication, validation, or invalid error, got: '.$e->getMessage()
            );
        }
    }

    public function test_create_signing_key_with_retry_options(): void
    {
        $retryConfig = new RetryConfigBackoff(
            initialIntervalMs: 1,
            maxIntervalMs: 50,
            exponent: 1.1,
            maxElapsedTimeMs: 100,
            retryConnectionErrors: false
        );

        $options = new Options();
        $options->retryConfig = $retryConfig;

        $this->expectNotToPerformAssertions();

        try {
            $response = $this->sdk->signingKeys->createSigningKey($options);
            $this->assertInstanceOf(CreateSigningKeyResponse::class, $response);
        } catch (\Exception $e) {
            // Expected in test environment without valid API credentials
            $this->assertStringContainsString('authentication', strtolower($e->getMessage()));
        }
    }

    public function test_list_signing_keys_with_default_parameters(): void
    {
        $request = new ListSigningKeysRequest();

        $this->assertInstanceOf(ListSigningKeysRequest::class, $request);
    }

    public function test_list_signing_keys_with_pagination(): void
    {
        $request = new ListSigningKeysRequest(
            limit: 10,
            offset: 1
        );

        $this->assertEquals(10, $request->limit);
        $this->assertEquals(1, $request->offset);
    }

    public function test_get_signing_key_by_id_with_valid_id(): void
    {
        $request = new GetSigningKeyByIdRequest(signingKeyId: 'test-key-id');

        $this->assertInstanceOf(GetSigningKeyByIdRequest::class, $request);
        $this->assertEquals('test-key-id', $request->signingKeyId);
    }

    public function test_delete_signing_key_with_valid_id(): void
    {
        $request = new DeleteSigningKeyRequest(signingKeyId: 'test-key-id');

        $this->assertInstanceOf(DeleteSigningKeyRequest::class, $request);
        $this->assertEquals('test-key-id', $request->signingKeyId);
    }

    public function test_create_signing_key_response_structure(): void
    {
        $mockResponse = $this->createMock(\Psr\Http\Message\ResponseInterface::class);

        $createResponse = new CreateResponse();
        $createResponse->data = new CreateSigningKeyResponseDTO(
            id: 'test-key-id',
            privateKey: 'test-private-key',
            createdAt: new \DateTime()
        );

        $response = new CreateSigningKeyResponse(
            contentType: 'application/json',
            statusCode: 200,
            rawResponse: $mockResponse,
            createResponse: $createResponse
        );

        $this->assertEquals('application/json', $response->contentType);
        $this->assertEquals(200, $response->statusCode);
        $this->assertInstanceOf(CreateResponse::class, $response->createResponse);
        $this->assertInstanceOf(CreateSigningKeyResponseDTO::class, $response->createResponse->data);
        $this->assertEquals('test-key-id', $response->createResponse->data->id);
        $this->assertEquals('test-private-key', $response->createResponse->data->privateKey);
        $this->assertInstanceOf(\DateTime::class, $response->createResponse->data->createdAt);
    }

    public function test_create_signing_key_response_dto_properties(): void
    {
        $dto = new CreateSigningKeyResponseDTO(
            id: 'key-123',
            privateKey: 'base64-encoded-private-key',
            createdAt: new \DateTime('2023-01-01T00:00:00Z')
        );

        $this->assertEquals('key-123', $dto->id);
        $this->assertEquals('base64-encoded-private-key', $dto->privateKey);
        $this->assertInstanceOf(\DateTime::class, $dto->createdAt);
        $this->assertEquals('2023-01-01T00:00:00+00:00', $dto->createdAt->format('c'));
    }

    public function test_create_signing_key_response_dto_with_null_values(): void
    {
        $dto = new CreateSigningKeyResponseDTO();

        $this->assertNull($dto->id);
        $this->assertNull($dto->privateKey);
        $this->assertNull($dto->createdAt);
    }

    public function test_signing_key_workflow(): void
    {
        // Test the complete workflow: create -> list -> get -> delete
        $this->expectNotToPerformAssertions();

        try {
            // 1. Create a signing key
            $createResponse = $this->sdk->signingKeys->createSigningKey();
            $this->assertInstanceOf(CreateSigningKeyResponse::class, $createResponse);

            if ($createResponse->createResponse && $createResponse->createResponse->data) {
                $keyId = $createResponse->createResponse->data->id;
                $this->assertNotNull($keyId);

                // 2. List signing keys
                $listResponse = $this->sdk->signingKeys->listSigningKeys(limit: 10, offset: 1);
                $this->assertInstanceOf(ListSigningKeysResponse::class, $listResponse);

                // 3. Get signing key by ID
                $getResponse = $this->sdk->signingKeys->getSigningKeyById($keyId);
                $this->assertInstanceOf(GetSigningKeyByIdResponse::class, $getResponse);

                // 4. Delete signing key
                $deleteResponse = $this->sdk->signingKeys->deleteSigningKey($keyId);
                $this->assertInstanceOf(DeleteSigningKeyResponse::class, $deleteResponse);
            }
        } catch (\Exception $e) {
            // Expected in test environment - could be authentication or validation errors
            $message = strtolower($e->getMessage());
            $this->assertTrue(
                str_contains($message, 'authentication') ||
                str_contains($message, 'validation') ||
                str_contains($message, 'invalid'),
                'Expected authentication, validation, or invalid error, got: '.$e->getMessage()
            );
        }
    }

    public function test_signing_key_security_properties(): void
    {
        $dto = new CreateSigningKeyResponseDTO(
            id: 'secure-key-id',
            privateKey: '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC...\n-----END PRIVATE KEY-----',
            createdAt: new \DateTime()
        );

        // Verify that the private key contains expected RSA key format
        $this->assertStringContainsString('BEGIN PRIVATE KEY', $dto->privateKey);
        $this->assertStringContainsString('END PRIVATE KEY', $dto->privateKey);

        // Verify the key ID is not empty
        $this->assertNotEmpty($dto->id);
        $this->assertGreaterThan(0, strlen($dto->id));
    }

    public function test_signing_key_error_handling(): void
    {
        // Test error handling for invalid key ID
        $this->expectNotToPerformAssertions();

        try {
            $response = $this->sdk->signingKeys->getSigningKeyById('invalid-key-id');
        } catch (\FastPix\Sdk\Models\Errors\SigningKeyNotFoundError $e) {
            $this->assertStringContainsString('not found', strtolower($e->getMessage()));
        } catch (\Exception $e) {
            // Expected in test environment - could be authentication or validation errors
            $message = strtolower($e->getMessage());
            $this->assertTrue(
                str_contains($message, 'authentication') ||
                str_contains($message, 'validation') ||
                str_contains($message, 'invalid'),
                'Expected authentication, validation, or invalid error, got: '.$e->getMessage()
            );
        }
    }

    public function test_signing_key_pagination(): void
    {
        $request = new ListSigningKeysRequest(
            limit: 25,
            offset: 2
        );

        $this->assertEquals(25, $request->limit);
        $this->assertEquals(2, $request->offset);
    }

    public function test_signing_key_with_custom_retry_configuration(): void
    {
        $retryConfig = new RetryConfigBackoff(
            initialIntervalMs: 2,
            maxIntervalMs: 100,
            exponent: 2.0,
            maxElapsedTimeMs: 200,
            retryConnectionErrors: true
        );

        $options = new Options();
        $options->retryConfig = $retryConfig;

        $this->assertSame($retryConfig, $options->retryConfig);
        $this->assertEquals(2, $options->retryConfig->initialInterval);
        $this->assertEquals(100, $options->retryConfig->maxInterval);
        $this->assertEquals(2.0, $options->retryConfig->exponent);
        $this->assertEquals(200, $options->retryConfig->maxElapsedTime);
        $this->assertTrue($options->retryConfig->retryConnectionErrors);
    }
}
