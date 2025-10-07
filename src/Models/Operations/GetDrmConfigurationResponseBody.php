<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** GetDrmConfigurationResponseBody - DRM configuration(s) retrieved successfully */
class GetDrmConfigurationResponseBody
{
    /**
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * $data
     *
     * @var ?array<Components\DrmIdResponse> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\DrmIdResponse>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $data = null;

    /**
     * Pagination organizes content into pages for better readability and navigation.
     *
     * @var ?Components\Pagination $pagination
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pagination')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\Pagination|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\Pagination $pagination = null;

    /**
     * @param  ?bool  $success
     * @param  ?array<Components\DrmIdResponse>  $data
     * @param  ?Components\Pagination  $pagination
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null, ?Components\Pagination $pagination = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->pagination = $pagination;
    }
}