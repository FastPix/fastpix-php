<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Models\Components;

use FastPix\Sdk\Models\Components\Security;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Models\Components\Security
 */
class SecurityTest extends TestCase
{
    public function test_security_can_be_created_with_username_and_password(): void
    {
        $security = new Security(
            username: '123e4567-e89b-12d3-a456-426614174000',
            password: '987fcdeb-51a2-43d1-b789-123456789abc'
        );

        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $security->username);
        $this->assertEquals('987fcdeb-51a2-43d1-b789-123456789abc', $security->password);
    }

    public function test_security_can_be_created_with_empty_credentials(): void
    {
        $security = new Security(
            username: '123e4567-e89b-12d3-a456-426614174000',
            password: '987fcdeb-51a2-43d1-b789-123456789abc'
        );

        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $security->username);
        $this->assertEquals('987fcdeb-51a2-43d1-b789-123456789abc', $security->password);
    }

    public function test_security_properties_are_public(): void
    {
        $security = new Security(
            username: '123e4567-e89b-12d3-a456-426614174000',
            password: '987fcdeb-51a2-43d1-b789-123456789abc'
        );

        $this->assertObjectHasProperty('username', $security);
        $this->assertObjectHasProperty('password', $security);
    }
}
