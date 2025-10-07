<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Hooks;

use FastPix\Sdk\Hooks\HookContext;
use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\SDKConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Hooks\HookContext
 */
class HookContextTest extends TestCase
{
    private SDKConfiguration $config;
    private HookContext $hookContext;

    protected function setUp(): void
    {
        $this->config = new SDKConfiguration();
        $this->config->serverUrl = 'https://api.fastpix.io/v1/';

        $this->hookContext = new HookContext(
            config: $this->config,
            baseURL: 'https://api.fastpix.io/v1/',
            operationID: 'test-operation',
            oauth2Scopes: ['read', 'write'],
            securitySource: function () {
                return new Security(
                    username: '123e4567-e89b-12d3-a456-426614174000',
                    password: '987fcdeb-51a2-43d1-b789-123456789abc'
                );
            }
        );
    }

    public function test_hook_context_can_be_created(): void
    {
        $this->assertInstanceOf(HookContext::class, $this->hookContext);
    }

    public function test_hook_context_has_config(): void
    {
        $this->assertObjectHasProperty('config', $this->hookContext);
        $this->assertInstanceOf(SDKConfiguration::class, $this->hookContext->config);
    }

    public function test_hook_context_has_base_url(): void
    {
        $this->assertObjectHasProperty('baseURL', $this->hookContext);
        $this->assertEquals('https://api.fastpix.io/v1/', $this->hookContext->baseURL);
    }

    public function test_hook_context_has_operation_id(): void
    {
        $this->assertObjectHasProperty('operationID', $this->hookContext);
        $this->assertEquals('test-operation', $this->hookContext->operationID);
    }

    public function test_hook_context_has_o_auth2_scopes(): void
    {
        $this->assertObjectHasProperty('oauth2Scopes', $this->hookContext);
        $this->assertEquals(['read', 'write'], $this->hookContext->oauth2Scopes);
    }

    public function test_hook_context_has_security_source(): void
    {
        $this->assertObjectHasProperty('securitySource', $this->hookContext);
        $this->assertIsCallable($this->hookContext->securitySource);
    }

    public function test_security_source_returns_security(): void
    {
        $security = ($this->hookContext->securitySource)();

        $this->assertInstanceOf(Security::class, $security);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $security->username);
        $this->assertEquals('987fcdeb-51a2-43d1-b789-123456789abc', $security->password);
    }

    public function test_hook_context_with_empty_o_auth2_scopes(): void
    {
        $hookContext = new HookContext(
            config: $this->config,
            baseURL: 'https://api.fastpix.io/v1/',
            operationID: 'test-operation',
            oauth2Scopes: [],
            securitySource: function () {
                return new Security(
                    username: '123e4567-e89b-12d3-a456-426614174000',
                    password: '987fcdeb-51a2-43d1-b789-123456789abc'
                );
            }
        );

        $this->assertEmpty($hookContext->oauth2Scopes);
    }

    public function test_hook_context_with_null_security_source(): void
    {
        $hookContext = new HookContext(
            config: $this->config,
            baseURL: 'https://api.fastpix.io/v1/',
            operationID: 'test-operation',
            oauth2Scopes: ['read'],
            securitySource: null
        );

        $this->assertNull($hookContext->securitySource);
    }
}
