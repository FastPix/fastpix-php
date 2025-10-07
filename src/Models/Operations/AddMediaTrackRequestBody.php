<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
class AddMediaTrackRequestBody
{
    /**
     * Contains details about the track being added to the media file.
     *
     * @var ?Components\AddTrackRequest $tracks
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('tracks')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\AddTrackRequest|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\AddTrackRequest $tracks = null;

    /**
     * @param  ?Components\AddTrackRequest  $tracks
     * @phpstan-pure
     */
    public function __construct(?Components\AddTrackRequest $tracks = null)
    {
        $this->tracks = $tracks;
    }
}