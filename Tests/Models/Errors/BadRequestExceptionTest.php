<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Models\Errors;

use FastPix\Sdk\Models\Errors\BadRequestException;
use PHPUnit\Framework\TestCase;

class BadRequestExceptionTest extends TestCase
{
    public function test_bad_request_exception_can_be_created(): void
    {
        $exception = new BadRequestException(
            message: 'Bad Request',
            statusCode: 400,
            rawResponse: null,
            body: '{"error": "Invalid request parameters"}'
        );

        $this->assertInstanceOf(BadRequestException::class, $exception);
        $this->assertEquals('Bad Request', $exception->getMessage());
        $this->assertEquals(400, $exception->statusCode);
        $this->assertNull($exception->rawResponse);
        $this->assertEquals('{"error": "Invalid request parameters"}', $exception->body);
    }

    public function test_bad_request_exception_inherits_from_api_exception(): void
    {
        $exception = new BadRequestException(
            message: 'Bad Request',
            statusCode: 400,
            rawResponse: null,
            body: '{}'
        );

        $this->assertInstanceOf(\FastPix\Sdk\Models\Errors\APIException::class, $exception);
    }

    public function test_bad_request_exception_properties_are_public(): void
    {
        $exception = new BadRequestException(
            message: 'Bad Request',
            statusCode: 400,
            rawResponse: null,
            body: '{}'
        );

        $this->assertObjectHasProperty('statusCode', $exception);
        $this->assertObjectHasProperty('rawResponse', $exception);
        $this->assertObjectHasProperty('body', $exception);
    }
}
