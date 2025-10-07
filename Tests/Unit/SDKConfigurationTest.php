<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit;

use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\SDKConfiguration;
use FastPix\Sdk\Utils\Retry\RetryConfigBackoff;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\SDKConfiguration
 */
class SDKConfigurationTest extends TestCase
{
    private SDKConfiguration $config;

    protected function setUp(): void
    {
        $this->config = new SDKConfiguration();
    }

    public function test_default_configuration(): void
    {
        $this->assertEquals('php', $this->config->language);
        $this->assertEquals('1.0.0', $this->config->sdkVersion);
        $this->assertEquals('1.0.0', $this->config->openapiDocVersion);
        $this->assertEquals('2.721.3', $this->config->genVersion);
        $this->assertEquals('fastpix-sdk/php 1.0.0 2.721.3 1.0.0 fastpix/sdk', $this->config->userAgent);
        $this->assertEquals(0, $this->config->serverIndex);
        $this->assertEquals('', $this->config->serverUrl);
    }

    public function test_get_server_url_with_custom_url(): void
    {
        $customUrl = 'https://api.fastpix.io/v1/';
        $this->config->serverUrl = $customUrl;

        $this->assertEquals($customUrl, $this->config->getServerUrl());
    }

    public function test_get_server_url_with_server_index(): void
    {
        $this->config->serverIndex = 0;
        $this->config->serverUrl = '';

        $serverUrl = $this->config->getServerUrl();
        $this->assertIsString($serverUrl);
        $this->assertNotEmpty($serverUrl);
    }

    public function test_has_security_returns_false_by_default(): void
    {
        $this->assertFalse($this->config->hasSecurity());
    }

    public function test_has_security_returns_true_when_set(): void
    {
        $this->config->securitySource = function () {
            return new Security(
                username: '123e4567-e89b-12d3-a456-426614174000',
                password: '987fcdeb-51a2-43d1-b789-123456789abc'
            );
        };

        $this->assertTrue($this->config->hasSecurity());
    }

    public function test_get_security_returns_null_by_default(): void
    {
        $this->assertFalse($this->config->hasSecurity());
    }

    public function test_get_security_returns_security_when_set(): void
    {
        $expectedSecurity = new Security(
            username: '123e4567-e89b-12d3-a456-426614174000',
            password: '987fcdeb-51a2-43d1-b789-123456789abc'
        );

        $this->config->securitySource = function () use ($expectedSecurity) {
            return $expectedSecurity;
        };

        $actualSecurity = $this->config->getSecurity();
        $this->assertInstanceOf(Security::class, $actualSecurity);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $actualSecurity->username);
        $this->assertEquals('987fcdeb-51a2-43d1-b789-123456789abc', $actualSecurity->password);
    }

    public function test_get_server_details_with_custom_url(): void
    {
        $customUrl = 'https://api.fastpix.io/v1/';
        $this->config->serverUrl = $customUrl;

        $serverDetails = $this->config->getServerDetails();
        $this->assertInstanceOf(\FastPix\Sdk\Utils\ServerDetails::class, $serverDetails);
        $this->assertEquals(rtrim($customUrl, '/'), $serverDetails->baseUrl);
    }

    public function test_get_templated_server_url(): void
    {
        $customUrl = 'https://api.fastpix.io/v1/';
        $this->config->serverUrl = $customUrl;

        $templatedUrl = $this->config->getTemplatedServerUrl();
        $this->assertIsString($templatedUrl);
        $this->assertStringContainsString('api.fastpix.io', $templatedUrl);
    }

    public function test_retry_config_can_be_set(): void
    {
        $retryConfig = new RetryConfigBackoff(
            initialIntervalMs: 1,
            maxIntervalMs: 50,
            exponent: 1.1,
            maxElapsedTimeMs: 100,
            retryConnectionErrors: false
        );

        $this->config->retryConfig = $retryConfig;

        $this->assertSame($retryConfig, $this->config->retryConfig);
    }

    public function test_hooks_are_initialized(): void
    {
        $this->assertInstanceOf(\FastPix\Sdk\Hooks\SDKHooks::class, $this->config->hooks);
    }
}
