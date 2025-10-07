<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** PlaybackIdResponse - A collection of Playback ID objects utilized for crafting HLS playback urls. */
class PlaybackIdResponse
{
    /**
     * Unique identifier for the playbackId
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * Determines if access to the streamed content is kept private or available to all.
     *
     * @var ?string $accessPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessPolicy')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $accessPolicy = null;

    /**
     * @param  ?string  $id
     * @param  ?string  $accessPolicy
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?string $accessPolicy = null)
    {
        $this->id = $id;
        $this->accessPolicy = $accessPolicy;
    }
}