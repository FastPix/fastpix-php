<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** GenerateSubtitleTrackResponseBody - Media details updated successfully */
class GenerateSubtitleTrackResponseBody
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
     * Represents the response for a successfully generated subtitle track.
     *
     * @var ?Components\GenerateTrackResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\GenerateTrackResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\GenerateTrackResponse $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\GenerateTrackResponse  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\GenerateTrackResponse $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}