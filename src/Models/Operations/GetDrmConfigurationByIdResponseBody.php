<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** GetDrmConfigurationByIdResponseBody - DRM configuration retrieved successfully */
class GetDrmConfigurationByIdResponseBody
{
    /**
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     *
     * @var ?Components\DrmIdResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\DrmIdResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\DrmIdResponse $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\DrmIdResponse  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\DrmIdResponse $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}