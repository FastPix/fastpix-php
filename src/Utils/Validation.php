<?php

declare(strict_types=1);

namespace FastPix\Sdk\Utils;

/**
 * Comprehensive input validation utility for the FastPix SDK
 */
class Validation
{
    /**
     * Validate and sanitize URL input
     */
    public static function validateUrl(string $url): string
    {
        $url = trim($url);

        if (empty($url)) {
            throw new \InvalidArgumentException('URL cannot be empty');
        }

        // Basic URL format validation
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid URL format: '.$url);
        }

        // Ensure HTTPS for security
        if (! str_starts_with(strtolower($url), 'https://')) {
            throw new \InvalidArgumentException('Only HTTPS URLs are allowed for security reasons');
        }

        return $url;
    }

    /**
     * Validate and sanitize media ID
     */
    public static function validateMediaId(string $mediaId): string
    {
        $mediaId = trim($mediaId);

        if (empty($mediaId)) {
            throw new \InvalidArgumentException('Media ID cannot be empty');
        }

        // UUID format validation
        if (! preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $mediaId)) {
            throw new \InvalidArgumentException('Invalid media ID format. Expected UUID format');
        }

        return $mediaId;
    }

    /**
     * Validate and sanitize stream ID
     */
    public static function validateStreamId(string $streamId): string
    {
        $streamId = trim($streamId);

        if (empty($streamId)) {
            throw new \InvalidArgumentException('Stream ID cannot be empty');
        }

        // Alphanumeric with hyphens and underscores
        if (! preg_match('/^[a-zA-Z0-9_-]+$/', $streamId)) {
            throw new \InvalidArgumentException('Invalid stream ID format. Only alphanumeric characters, hyphens, and underscores are allowed');
        }

        if (strlen($streamId) > 100) {
            throw new \InvalidArgumentException('Stream ID cannot exceed 100 characters');
        }

        return $streamId;
    }

    /**
     * Validate and sanitize metadata
     */
    public static function validateMetadata(array $metadata): array
    {
        $sanitized = [];

        foreach ($metadata as $key => $value) {
            // Validate key
            $key = trim((string) $key);
            if (empty($key)) {
                continue; // Skip empty keys
            }

            if (strlen($key) > 100) {
                throw new \InvalidArgumentException('Metadata key cannot exceed 100 characters: '.$key);
            }

            // Validate value
            if (is_string($value)) {
                $value = trim($value);
                if (strlen($value) > 1000) {
                    throw new \InvalidArgumentException('Metadata value cannot exceed 1000 characters for key: '.$key);
                }
            } elseif (! is_numeric($value) && ! is_bool($value)) {
                throw new \InvalidArgumentException('Metadata value must be string, number, or boolean for key: '.$key);
            }

            $sanitized[$key] = $value;
        }

        if (count($sanitized) > 50) {
            throw new \InvalidArgumentException('Cannot have more than 50 metadata entries');
        }

        return $sanitized;
    }

    /**
     * Validate and sanitize access policy
     */
    public static function validateAccessPolicy(string $policy): string
    {
        $policy = trim($policy);
        $allowedPolicies = ['public', 'private', 'signed'];

        if (! in_array(strtolower($policy), $allowedPolicies, true)) {
            throw new \InvalidArgumentException('Invalid access policy. Must be one of: '.implode(', ', $allowedPolicies));
        }

        return strtolower($policy);
    }

    /**
     * Validate and sanitize date range
     */
    public static function validateDateRange(string $startDate, string $endDate): array
    {
        $startDate = trim($startDate);
        $endDate = trim($endDate);

        // Validate date format (YYYY-MM-DD)
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) {
            throw new \InvalidArgumentException('Invalid start date format. Expected YYYY-MM-DD');
        }

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            throw new \InvalidArgumentException('Invalid end date format. Expected YYYY-MM-DD');
        }

        // Validate dates are valid
        $start = \DateTime::createFromFormat('Y-m-d', $startDate);
        $end = \DateTime::createFromFormat('Y-m-d', $endDate);

        if (! $start || $start->format('Y-m-d') !== $startDate) {
            throw new \InvalidArgumentException('Invalid start date: '.$startDate);
        }

        if (! $end || $end->format('Y-m-d') !== $endDate) {
            throw new \InvalidArgumentException('Invalid end date: '.$endDate);
        }

        // Validate date range
        if ($start > $end) {
            throw new \InvalidArgumentException('Start date cannot be after end date');
        }

        // Validate range is not too large (max 1 year)
        $diff = $end->diff($start);
        if ($diff->days > 365) {
            throw new \InvalidArgumentException('Date range cannot exceed 365 days');
        }

        return [$startDate, $endDate];
    }

    /**
     * Validate and sanitize pagination parameters
     */
    public static function validatePagination(int $offset = 1, int $limit = 10): array
    {
        if ($offset < 1) {
            throw new \InvalidArgumentException('Offset must be greater than 0');
        }

        if ($limit < 1 || $limit > 100) {
            throw new \InvalidArgumentException('Limit must be between 1 and 100');
        }

        return [$offset, $limit];
    }

    /**
     * Validate and sanitize language code
     */
    public static function validateLanguageCode(string $language): string
    {
        $language = trim($language);

        if (empty($language)) {
            throw new \InvalidArgumentException('Language code cannot be empty');
        }

        // ISO 639-1 format (2 characters)
        if (! preg_match('/^[a-z]{2}$/', $language)) {
            throw new \InvalidArgumentException('Invalid language code format. Expected 2-letter ISO 639-1 code (e.g., "en", "es")');
        }

        return strtolower($language);
    }

    /**
     * Validate and sanitize file size
     */
    public static function validateFileSize(int $size): int
    {
        if ($size < 0) {
            throw new \InvalidArgumentException('File size cannot be negative');
        }

        // Max 10GB
        $maxSize = 10 * 1024 * 1024 * 1024;
        if ($size > $maxSize) {
            throw new \InvalidArgumentException('File size cannot exceed 10GB');
        }

        return $size;
    }

    /**
     * Validate and sanitize dimension name
     */
    public static function validateDimension(string $dimension): string
    {
        $dimension = trim($dimension);
        $allowedDimensions = ['country', 'device', 'browser', 'os', 'referrer'];

        if (! in_array(strtolower($dimension), $allowedDimensions, true)) {
            throw new \InvalidArgumentException('Invalid dimension. Must be one of: '.implode(', ', $allowedDimensions));
        }

        return strtolower($dimension);
    }

    /**
     * Sanitize string input
     */
    public static function sanitizeString(string $input, int $maxLength = 1000): string
    {
        $input = trim($input);

        // Remove null bytes and control characters
        $input = str_replace(["\0", "\r"], '', $input);

        // Limit length
        if (strlen($input) > $maxLength) {
            $input = substr($input, 0, $maxLength);
        }

        return $input;
    }

    /**
     * Validate API credentials
     */
    public static function validateCredentials(string $accessToken, string $secretKey): array
    {
        $accessToken = trim($accessToken);
        $secretKey = trim($secretKey);

        if (empty($accessToken)) {
            throw new \InvalidArgumentException('Access token cannot be empty');
        }

        if (empty($secretKey)) {
            throw new \InvalidArgumentException('Secret key cannot be empty');
        }

        // Basic format validation for UUID-like tokens
        if (! preg_match('/^[a-f0-9-]{20,}$/i', $accessToken)) {
            throw new \InvalidArgumentException('Invalid access token format');
        }

        if (! preg_match('/^[a-f0-9-]{20,}$/i', $secretKey)) {
            throw new \InvalidArgumentException('Invalid secret key format');
        }

        return [$accessToken, $secretKey];
    }
}
