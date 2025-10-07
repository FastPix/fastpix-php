<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Utils\Retry;

use FastPix\Sdk\Utils\Retry\RetryConfigBackoff;
use FastPix\Sdk\Utils\Retry\RetryConfigNone;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Utils\Retry\RetryConfig
 */
class RetryConfigTest extends TestCase
{
    public function test_retry_config_backoff_can_be_created(): void
    {
        $retryConfig = new RetryConfigBackoff(
            initialIntervalMs: 1,
            maxIntervalMs: 50,
            exponent: 1.1,
            maxElapsedTimeMs: 100,
            retryConnectionErrors: false
        );

        $this->assertEquals(1, $retryConfig->initialInterval);
        $this->assertEquals(50, $retryConfig->maxInterval);
        $this->assertEquals(1.1, $retryConfig->exponent);
        $this->assertEquals(100, $retryConfig->maxElapsedTime);
        $this->assertFalse($retryConfig->retryConnectionErrors);
    }

    public function test_retry_config_backoff_with_retry_connection_errors(): void
    {
        $retryConfig = new RetryConfigBackoff(
            initialIntervalMs: 2,
            maxIntervalMs: 100,
            exponent: 2.0,
            maxElapsedTimeMs: 200,
            retryConnectionErrors: true
        );

        $this->assertEquals(2, $retryConfig->initialInterval);
        $this->assertEquals(100, $retryConfig->maxInterval);
        $this->assertEquals(2.0, $retryConfig->exponent);
        $this->assertEquals(200, $retryConfig->maxElapsedTime);
        $this->assertTrue($retryConfig->retryConnectionErrors);
    }

    public function test_retry_config_none_can_be_created(): void
    {
        $retryConfig = new RetryConfigNone();

        $this->assertInstanceOf(RetryConfigNone::class, $retryConfig);
    }

    public function test_retry_config_backoff_properties_are_public(): void
    {
        $retryConfig = new RetryConfigBackoff(
            initialIntervalMs: 1,
            maxIntervalMs: 50,
            exponent: 1.1,
            maxElapsedTimeMs: 100,
            retryConnectionErrors: false
        );

        $this->assertObjectHasProperty('initialInterval', $retryConfig);
        $this->assertObjectHasProperty('maxInterval', $retryConfig);
        $this->assertObjectHasProperty('exponent', $retryConfig);
        $this->assertObjectHasProperty('maxElapsedTime', $retryConfig);
        $this->assertObjectHasProperty('retryConnectionErrors', $retryConfig);
    }
}
