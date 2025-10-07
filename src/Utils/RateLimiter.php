<?php

declare(strict_types=1);

namespace FastPix\Sdk\Utils;

/**
 * Rate limiting utility for the FastPix SDK
 */
class RateLimiter
{
    private int $maxRequests;
    private int $timeWindow;
    private array $requests = [];
    private ?Logger $logger;

    public function __construct(
        int $maxRequests = 100,
        int $timeWindowSeconds = 60,
        ?Logger $logger = null
    ) {
        $this->maxRequests = $maxRequests;
        $this->timeWindow = $timeWindowSeconds;
        $this->logger = $logger;
    }

    /**
     * Check if request is allowed and record it
     */
    public function isAllowed(string $identifier = 'default'): bool
    {
        $now = time();
        $this->cleanupOldRequests($now);

        if (! isset($this->requests[$identifier])) {
            $this->requests[$identifier] = [];
        }

        $requestCount = count($this->requests[$identifier]);

        if ($requestCount >= $this->maxRequests) {
            $this->logger?->warning('Rate limit exceeded', [
                'identifier' => $identifier,
                'request_count' => $requestCount,
                'max_requests' => $this->maxRequests,
                'time_window' => $this->timeWindow,
            ]);

            return false;
        }

        $this->requests[$identifier][] = $now;

        $this->logger?->debug('Request allowed', [
            'identifier' => $identifier,
            'request_count' => $requestCount + 1,
            'max_requests' => $this->maxRequests,
        ]);

        return true;
    }

    /**
     * Get remaining requests for identifier
     */
    public function getRemainingRequests(string $identifier = 'default'): int
    {
        $now = time();
        $this->cleanupOldRequests($now);

        if (! isset($this->requests[$identifier])) {
            return $this->maxRequests;
        }

        $requestCount = count($this->requests[$identifier]);

        return max(0, $this->maxRequests - $requestCount);
    }

    /**
     * Get reset time for rate limit window
     */
    public function getResetTime(string $identifier = 'default'): int
    {
        if (! isset($this->requests[$identifier]) || empty($this->requests[$identifier])) {
            return time();
        }

        $oldestRequest = min($this->requests[$identifier]);

        return $oldestRequest + $this->timeWindow;
    }

    /**
     * Wait for rate limit reset
     */
    public function waitForReset(string $identifier = 'default'): void
    {
        $resetTime = $this->getResetTime($identifier);
        $waitTime = $resetTime - time();

        if ($waitTime > 0) {
            $this->logger?->info('Waiting for rate limit reset', [
                'identifier' => $identifier,
                'wait_seconds' => $waitTime,
            ]);

            sleep($waitTime);
        }
    }

    /**
     * Clean up old requests outside the time window
     */
    private function cleanupOldRequests(int $now): void
    {
        $cutoff = $now - $this->timeWindow;

        foreach ($this->requests as $identifier => $timestamps) {
            $this->requests[$identifier] = array_filter(
                $timestamps,
                fn (int $timestamp) => $timestamp > $cutoff
            );

            // Remove empty arrays
            if (empty($this->requests[$identifier])) {
                unset($this->requests[$identifier]);
            }
        }
    }

    /**
     * Create rate limiter with default settings
     */
    public static function createDefault(): self
    {
        $maxRequests = (int) ($_ENV['FASTPIX_RATE_LIMIT_MAX_REQUESTS'] ?? 100);
        $timeWindow = (int) ($_ENV['FASTPIX_RATE_LIMIT_TIME_WINDOW'] ?? 60);

        return new self($maxRequests, $timeWindow, Logger::createDefault());
    }

    /**
     * Create rate limiter for high-volume operations
     */
    public static function createForHighVolume(): self
    {
        return new self(1000, 60, Logger::createDefault());
    }

    /**
     * Create rate limiter for conservative operations
     */
    public static function createForConservative(): self
    {
        return new self(10, 60, Logger::createDefault());
    }
}
