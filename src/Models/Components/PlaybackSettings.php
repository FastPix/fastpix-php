<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** PlaybackSettings - Displays the result of the playback settings. */
class PlaybackSettings
{
    /**
     * Basic access policy for media content
     *
     * @var ?BasicAccessPolicy $accessPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessPolicy')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\BasicAccessPolicy|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?BasicAccessPolicy $accessPolicy = null;

    /**
     * @param  ?BasicAccessPolicy  $accessPolicy
     * @phpstan-pure
     */
    public function __construct(?BasicAccessPolicy $accessPolicy = null)
    {
        $this->accessPolicy = $accessPolicy;
    }
}