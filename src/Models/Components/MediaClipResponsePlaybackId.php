<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class MediaClipResponsePlaybackId
{
    /**
     * The unique identifier for playback.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * The access policy of the playback.
     *
     * @var ?string $accessPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessPolicy')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $accessPolicy = null;

    /**
     *
     * @var ?MediaClipResponseAccessRestrictions $accessRestrictions
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessRestrictions')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaClipResponseAccessRestrictions|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?MediaClipResponseAccessRestrictions $accessRestrictions = null;

    /**
     * @param  ?string  $id
     * @param  ?string  $accessPolicy
     * @param  ?MediaClipResponseAccessRestrictions  $accessRestrictions
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?string $accessPolicy = null, ?MediaClipResponseAccessRestrictions $accessRestrictions = null)
    {
        $this->id = $id;
        $this->accessPolicy = $accessPolicy;
        $this->accessRestrictions = $accessRestrictions;
    }
}