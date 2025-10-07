<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class ModerationResponse
{
    /**
     *
     * @var ?string $mediaId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $mediaId = null;

    /**
     *
     * @var ?bool $isModerationEnabled
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('isModerationEnabled')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $isModerationEnabled = null;

    /**
     * @param  ?string  $mediaId
     * @param  ?bool  $isModerationEnabled
     * @phpstan-pure
     */
    public function __construct(?string $mediaId = null, ?bool $isModerationEnabled = null)
    {
        $this->mediaId = $mediaId;
        $this->isModerationEnabled = $isModerationEnabled;
    }
}