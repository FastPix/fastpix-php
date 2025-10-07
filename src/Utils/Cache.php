<?php

declare(strict_types=1);

namespace FastPix\Sdk\Utils;

/**
 * Simple in-memory cache utility for the FastPix SDK
 */
class Cache
{
    private array $cache = [];
    private array $expiry = [];
    private int $maxSize;
    private ?Logger $logger;

    public function __construct(int $maxSize = 1000, ?Logger $logger = null)
    {
        $this->maxSize = $maxSize;
        $this->logger = $logger;
    }

    /**
     * Get value from cache
     */
    public function get(string $key): mixed
    {
        if (! $this->has($key)) {
            $this->logger?->debug('Cache miss', ['key' => $key]);

            return null;
        }

        $this->logger?->debug('Cache hit', ['key' => $key]);

        return $this->cache[$key];
    }

    /**
     * Set value in cache with optional TTL
     */
    public function set(string $key, mixed $value, ?int $ttlSeconds = null): void
    {
        // Clean up expired entries if cache is getting full
        if (count($this->cache) >= $this->maxSize) {
            $this->cleanup();
        }

        $this->cache[$key] = $value;

        if ($ttlSeconds !== null) {
            $this->expiry[$key] = time() + $ttlSeconds;
        }

        $this->logger?->debug('Cache set', [
            'key' => $key,
            'ttl_seconds' => $ttlSeconds,
            'cache_size' => count($this->cache),
        ]);
    }

    /**
     * Check if key exists in cache and is not expired
     */
    public function has(string $key): bool
    {
        if (! isset($this->cache[$key])) {
            return false;
        }

        // Check if expired
        if (isset($this->expiry[$key]) && time() > $this->expiry[$key]) {
            $this->delete($key);

            return false;
        }

        return true;
    }

    /**
     * Delete key from cache
     */
    public function delete(string $key): void
    {
        unset($this->cache[$key]);
        unset($this->expiry[$key]);

        $this->logger?->debug('Cache delete', ['key' => $key]);
    }

    /**
     * Clear all cache entries
     */
    public function clear(): void
    {
        $this->cache = [];
        $this->expiry = [];

        $this->logger?->info('Cache cleared');
    }

    /**
     * Get cache statistics
     */
    public function getStats(): array
    {
        $this->cleanup();

        return [
            'size' => count($this->cache),
            'max_size' => $this->maxSize,
            'usage_percentage' => round((count($this->cache) / $this->maxSize) * 100, 2),
        ];
    }

    /**
     * Clean up expired entries
     */
    private function cleanup(): void
    {
        $now = time();
        $expiredKeys = [];

        foreach ($this->expiry as $key => $expiryTime) {
            if ($now > $expiryTime) {
                $expiredKeys[] = $key;
            }
        }

        foreach ($expiredKeys as $key) {
            $this->delete($key);
        }

        if (! empty($expiredKeys)) {
            $this->logger?->debug('Cache cleanup', [
                'expired_keys' => count($expiredKeys),
                'remaining_size' => count($this->cache),
            ]);
        }
    }

    /**
     * Create cache with default settings
     */
    public static function createDefault(): self
    {
        $maxSize = (int) ($_ENV['FASTPIX_CACHE_MAX_SIZE'] ?? 1000);

        return new self($maxSize, Logger::createDefault());
    }

    /**
     * Create cache for high-memory environments
     */
    public static function createForHighMemory(): self
    {
        return new self(10000, Logger::createDefault());
    }

    /**
     * Create cache for low-memory environments
     */
    public static function createForLowMemory(): self
    {
        return new self(100, Logger::createDefault());
    }
}
