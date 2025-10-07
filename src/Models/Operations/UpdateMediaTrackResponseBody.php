<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** UpdateMediaTrackResponseBody - Media details updated successfully */
class UpdateMediaTrackResponseBody
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
     * Contains details about the track that was added or updated.
     *
     * @var ?Components\UpdateTrackResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\UpdateTrackResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\UpdateTrackResponse $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\UpdateTrackResponse  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\UpdateTrackResponse $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}