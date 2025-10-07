<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Utils;

use FastPix\Sdk\Utils\RateLimiter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Utils\RateLimiter
 */
class RateLimiterTest extends TestCase
{
    public function test_rate_limiter_allows_requests_within_limit(): void
    {
        $rateLimiter = new RateLimiter(2, 60); // 2 requests per minute

        $this->assertTrue($rateLimiter->isAllowed('test'));
        $this->assertTrue($rateLimiter->isAllowed('test'));
    }

    public function test_rate_limiter_blocks_requests_over_limit(): void
    {
        $rateLimiter = new RateLimiter(2, 60); // 2 requests per minute

        $this->assertTrue($rateLimiter->isAllowed('test'));
        $this->assertTrue($rateLimiter->isAllowed('test'));
        $this->assertFalse($rateLimiter->isAllowed('test'));
    }

    public function test_rate_limiter_tracks_different_identifiers(): void
    {
        $rateLimiter = new RateLimiter(1, 60); // 1 request per minute

        $this->assertTrue($rateLimiter->isAllowed('user1'));
        $this->assertTrue($rateLimiter->isAllowed('user2'));
        $this->assertFalse($rateLimiter->isAllowed('user1'));
    }

    public function test_get_remaining_requests(): void
    {
        $rateLimiter = new RateLimiter(5, 60);

        $this->assertEquals(5, $rateLimiter->getRemainingRequests('test'));

        $rateLimiter->isAllowed('test');
        $this->assertEquals(4, $rateLimiter->getRemainingRequests('test'));
    }

    public function test_get_reset_time(): void
    {
        $rateLimiter = new RateLimiter(1, 60);
        $rateLimiter->isAllowed('test');

        $resetTime = $rateLimiter->getResetTime('test');
        $this->assertGreaterThan(time(), $resetTime);
    }

    public function test_create_default_rate_limiter(): void
    {
        $rateLimiter = RateLimiter::createDefault();

        $this->assertInstanceOf(RateLimiter::class, $rateLimiter);
    }

    public function test_create_high_volume_rate_limiter(): void
    {
        $rateLimiter = RateLimiter::createForHighVolume();

        $this->assertInstanceOf(RateLimiter::class, $rateLimiter);
    }

    public function test_create_conservative_rate_limiter(): void
    {
        $rateLimiter = RateLimiter::createForConservative();

        $this->assertInstanceOf(RateLimiter::class, $rateLimiter);
    }
}
