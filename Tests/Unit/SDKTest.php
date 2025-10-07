<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit;

use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\SDK;
use FastPix\Sdk\SDKConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\SDK
 */
class SDKTest extends TestCase
{
    public function test_sdk_builder_creates_sdk_instance(): void
    {
        $sdk = SDK::builder()->build();

        $this->assertInstanceOf(SDK::class, $sdk);
    }

    public function test_sdk_builder_with_security(): void
    {
        $security = new Security(
            username: '123e4567-e89b-12d3-a456-426614174000',
            password: '987fcdeb-51a2-43d1-b789-123456789abc'
        );

        $sdk = SDK::builder()
            ->setSecurity($security)
            ->build();

        $this->assertInstanceOf(SDK::class, $sdk);
    }

    public function test_sdk_builder_with_server_url(): void
    {
        $serverUrl = 'https://api.fastpix.io/v1/';

        $sdk = SDK::builder()
            ->setServerUrl($serverUrl)
            ->build();

        $this->assertInstanceOf(SDK::class, $sdk);
    }

    public function test_sdk_builder_with_retry_config(): void
    {
        $retryConfig = new \FastPix\Sdk\Utils\Retry\RetryConfigBackoff(
            initialIntervalMs: 1,
            maxIntervalMs: 50,
            exponent: 1.1,
            maxElapsedTimeMs: 100,
            retryConnectionErrors: false
        );

        $sdk = SDK::builder()
            ->setRetryConfig($retryConfig)
            ->build();

        $this->assertInstanceOf(SDK::class, $sdk);
    }

    public function test_sdk_has_all_required_services(): void
    {
        $sdk = SDK::builder()->build();

        $this->assertObjectHasProperty('inputVideo', $sdk);
        $this->assertObjectHasProperty('manageVideos', $sdk);
        $this->assertObjectHasProperty('playback', $sdk);
        $this->assertObjectHasProperty('playlist', $sdk);
        $this->assertObjectHasProperty('startLiveStream', $sdk);
        $this->assertObjectHasProperty('manageLiveStream', $sdk);
        $this->assertObjectHasProperty('livePlayback', $sdk);
        $this->assertObjectHasProperty('simulcastStream', $sdk);
        $this->assertObjectHasProperty('signingKeys', $sdk);
        $this->assertObjectHasProperty('drmConfigurations', $sdk);
        $this->assertObjectHasProperty('inVideoAIFeatures', $sdk);
        $this->assertObjectHasProperty('metrics', $sdk);
        $this->assertObjectHasProperty('views', $sdk);
        $this->assertObjectHasProperty('dimensions', $sdk);
        $this->assertObjectHasProperty('errors', $sdk);
    }

    public function test_sdk_configuration_defaults(): void
    {
        $config = new SDKConfiguration();

        $this->assertEquals('php', $config->language);
        $this->assertEquals('1.0.0', $config->sdkVersion);
        $this->assertEquals('1.0.0', $config->openapiDocVersion);
        $this->assertEquals('2.721.3', $config->genVersion);
        $this->assertEquals('fastpix-sdk/php 1.0.0 2.721.3 1.0.0 fastpix/sdk', $config->userAgent);
    }

    public function test_sdk_configuration_server_url(): void
    {
        $config = new SDKConfiguration();
        $config->serverUrl = 'https://api.fastpix.io/v1/';

        $this->assertEquals('https://api.fastpix.io/v1/', $config->getServerUrl());
    }

    public function test_sdk_configuration_has_security(): void
    {
        $config = new SDKConfiguration();

        $this->assertFalse($config->hasSecurity());

        $config->securitySource = function () {
            return new Security(username: 'test', password: 'test');
        };

        $this->assertTrue($config->hasSecurity());
    }
}
