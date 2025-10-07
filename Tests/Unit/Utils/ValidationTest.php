<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Utils;

use FastPix\Sdk\Utils\Validation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Utils\Validation
 */
class ValidationTest extends TestCase
{
    public function test_validate_url_with_valid_https_url(): void
    {
        $url = 'https://example.com/video.mp4';
        $result = Validation::validateUrl($url);

        $this->assertEquals($url, $result);
    }

    public function test_validate_url_rejects_http_url(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only HTTPS URLs are allowed');

        Validation::validateUrl('http://example.com/video.mp4');
    }

    public function test_validate_url_rejects_invalid_url(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL format');

        Validation::validateUrl('not-a-url');
    }

    public function test_validate_media_id_with_valid_uuid(): void
    {
        $mediaId = '123e4567-e89b-12d3-a456-426614174000';
        $result = Validation::validateMediaId($mediaId);

        $this->assertEquals($mediaId, $result);
    }

    public function test_validate_media_id_rejects_invalid_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid media ID format');

        Validation::validateMediaId('invalid-id');
    }

    public function test_validate_metadata_with_valid_data(): void
    {
        $metadata = [
            'title' => 'Test Video',
            'description' => 'Test Description',
            'category' => 'test',
        ];

        $result = Validation::validateMetadata($metadata);

        $this->assertEquals($metadata, $result);
    }

    public function test_validate_metadata_rejects_too_many_entries(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot have more than 50 metadata entries');

        $metadata = [];
        for ($i = 0; $i < 51; $i++) {
            $metadata["key{$i}"] = "value{$i}";
        }

        Validation::validateMetadata($metadata);
    }

    public function test_validate_access_policy_with_valid_policy(): void
    {
        $result = Validation::validateAccessPolicy('public');

        $this->assertEquals('public', $result);
    }

    public function test_validate_access_policy_rejects_invalid_policy(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid access policy');

        Validation::validateAccessPolicy('invalid');
    }

    public function test_validate_date_range_with_valid_dates(): void
    {
        [$start, $end] = Validation::validateDateRange('2024-01-01', '2024-01-31');

        $this->assertEquals('2024-01-01', $start);
        $this->assertEquals('2024-01-31', $end);
    }

    public function test_validate_date_range_rejects_invalid_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid start date format');

        Validation::validateDateRange('01-01-2024', '2024-01-31');
    }

    public function test_validate_pagination_with_valid_values(): void
    {
        [$offset, $limit] = Validation::validatePagination(1, 10);

        $this->assertEquals(1, $offset);
        $this->assertEquals(10, $limit);
    }

    public function test_validate_pagination_rejects_invalid_offset(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Offset must be greater than 0');

        Validation::validatePagination(0, 10);
    }

    public function test_validate_credentials_with_valid_credentials(): void
    {
        [$token, $secret] = Validation::validateCredentials(
            '123e4567-e89b-12d3-a456-426614174000',
            '987fcdeb-51a2-43d1-b789-123456789abc'
        );

        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $token);
        $this->assertEquals('987fcdeb-51a2-43d1-b789-123456789abc', $secret);
    }

    public function test_validate_credentials_rejects_empty_token(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Access token cannot be empty');

        Validation::validateCredentials('', 'secret');
    }
}
