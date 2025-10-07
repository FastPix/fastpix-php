<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Models\Errors;

use FastPix\Sdk\Models\Errors\APIException;
use PHPUnit\Framework\TestCase;

class APIExceptionTest extends TestCase
{
    public function test_api_exception_can_be_created(): void
    {
        $exception = new APIException(
            message: 'Test error message',
            statusCode: 400,
            rawResponse: null,
            body: '{"error": "Bad Request"}'
        );

        $this->assertInstanceOf(APIException::class, $exception);
        $this->assertEquals('Test error message', $exception->getMessage());
        $this->assertEquals(400, $exception->statusCode);
        $this->assertNull($exception->rawResponse);
        $this->assertEquals('{"error": "Bad Request"}', $exception->body);
    }

    public function test_api_exception_with_raw_response(): void
    {
        $mockResponse = $this->createMock(\Psr\Http\Message\ResponseInterface::class);

        $exception = new APIException(
            message: 'Server error',
            statusCode: 500,
            rawResponse: $mockResponse,
            body: '{"error": "Internal Server Error"}'
        );

        $this->assertEquals('Server error', $exception->getMessage());
        $this->assertEquals(500, $exception->statusCode);
        $this->assertSame($mockResponse, $exception->rawResponse);
        $this->assertEquals('{"error": "Internal Server Error"}', $exception->body);
    }

    public function test_api_exception_properties_are_public(): void
    {
        $exception = new APIException(
            message: 'Test',
            statusCode: 400,
            rawResponse: null,
            body: '{}'
        );

        $this->assertObjectHasProperty('statusCode', $exception);
        $this->assertObjectHasProperty('rawResponse', $exception);
        $this->assertObjectHasProperty('body', $exception);
    }

    public function test_api_exception_inherits_from_exception(): void
    {
        $exception = new APIException(
            message: 'Test',
            statusCode: 400,
            rawResponse: null,
            body: '{}'
        );

        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
