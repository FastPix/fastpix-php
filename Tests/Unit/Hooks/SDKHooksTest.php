<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Hooks;

use FastPix\Sdk\Hooks\AfterErrorHook;
use FastPix\Sdk\Hooks\AfterSuccessHook;
use FastPix\Sdk\Hooks\BeforeRequestHook;
use FastPix\Sdk\Hooks\SDKHooks;
use FastPix\Sdk\Hooks\SDKInitHook;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Hooks\SDKHooks
 */
class SDKHooksTest extends TestCase
{
    private SDKHooks $hooks;

    protected function setUp(): void
    {
        $this->hooks = new SDKHooks();
    }

    public function test_sdk_hooks_can_be_created(): void
    {
        $this->assertInstanceOf(SDKHooks::class, $this->hooks);
    }

    public function test_sdk_hooks_has_before_request_hook(): void
    {
        $this->assertTrue(method_exists($this->hooks, 'registerBeforeRequestHook'));
    }

    public function test_sdk_hooks_has_after_success_hook(): void
    {
        $this->assertTrue(method_exists($this->hooks, 'registerAfterSuccessHook'));
    }

    public function test_sdk_hooks_has_after_error_hook(): void
    {
        $this->assertTrue(method_exists($this->hooks, 'registerAfterErrorHook'));
    }

    public function test_sdk_hooks_has_sdk_init_hook(): void
    {
        $this->assertTrue(method_exists($this->hooks, 'registerSDKInitHook'));
    }

    public function test_before_request_hook_can_be_set(): void
    {
        $mock = $this->createMock(BeforeRequestHook::class);
        $this->hooks->registerBeforeRequestHook($mock);
        $this->assertTrue(true);
    }

    public function test_after_success_hook_can_be_set(): void
    {
        $mock = $this->createMock(AfterSuccessHook::class);
        $this->hooks->registerAfterSuccessHook($mock);
        $this->assertTrue(true);
    }

    public function test_after_error_hook_can_be_set(): void
    {
        $mock = $this->createMock(AfterErrorHook::class);
        $this->hooks->registerAfterErrorHook($mock);
        $this->assertTrue(true);
    }

    public function test_sdk_init_hook_can_be_set(): void
    {
        $mock = $this->createMock(SDKInitHook::class);
        $this->hooks->registerSDKInitHook($mock);
        $this->assertTrue(true);
    }
}
