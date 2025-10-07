<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** AddMediaTrackResponseBody - Media details updated successfully */
class AddMediaTrackResponseBody
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
     * @var ?Components\AddTrackResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\AddTrackResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\AddTrackResponse $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\AddTrackResponse  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\AddTrackResponse $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}