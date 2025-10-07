<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Models\Operations;

use FastPix\Sdk\Models\Components\CreateResponse;
use FastPix\Sdk\Models\Components\CreateSigningKeyResponseDTO;
use FastPix\Sdk\Models\Operations\CreateSigningKeyResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Models\Operations\CreateSigningKeyResponse
 */
class CreateSigningKeyResponseTest extends TestCase
{
    public function test_create_signing_key_response_can_be_created(): void
    {
        $mockResponse = $this->createMock(\Psr\Http\Message\ResponseInterface::class);

        $response = new CreateSigningKeyResponse(
            contentType: 'application/json',
            statusCode: 200,
            rawResponse: $mockResponse
        );

        $this->assertEquals('application/json', $response->contentType);
        $this->assertEquals(200, $response->statusCode);
        $this->assertSame($mockResponse, $response->rawResponse);
        $this->assertNull($response->createResponse);
    }

    public function test_create_signing_key_response_with_create_response(): void
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
            statusCode: 201,
            rawResponse: $mockResponse,
            createResponse: $createResponse
        );

        $this->assertEquals('application/json', $response->contentType);
        $this->assertEquals(201, $response->statusCode);
        $this->assertSame($mockResponse, $response->rawResponse);
        $this->assertInstanceOf(CreateResponse::class, $response->createResponse);
        $this->assertInstanceOf(CreateSigningKeyResponseDTO::class, $response->createResponse->data);
    }

    public function test_create_signing_key_response_properties_are_public(): void
    {
        $mockResponse = $this->createMock(\Psr\Http\Message\ResponseInterface::class);

        $response = new CreateSigningKeyResponse(
            contentType: 'application/json',
            statusCode: 200,
            rawResponse: $mockResponse
        );

        $this->assertObjectHasProperty('contentType', $response);
        $this->assertObjectHasProperty('statusCode', $response);
        $this->assertObjectHasProperty('rawResponse', $response);
        $this->assertObjectHasProperty('createResponse', $response);
    }

    public function test_create_signing_key_response_with_error_status_code(): void
    {
        $mockResponse = $this->createMock(\Psr\Http\Message\ResponseInterface::class);

        $response = new CreateSigningKeyResponse(
            contentType: 'application/json',
            statusCode: 400,
            rawResponse: $mockResponse
        );

        $this->assertEquals(400, $response->statusCode);
        $this->assertNull($response->createResponse);
    }
}
