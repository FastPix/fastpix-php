<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** CancelUploadResponseBody - Upload cancelled successfully */
class CancelUploadResponseBody
{
    /**
     * Demonstrates whether the request is successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * Response returned when an upload is cancelled.
     *
     * @var ?Components\MediaCancelResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaCancelResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\MediaCancelResponse $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\MediaCancelResponse  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\MediaCancelResponse $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}