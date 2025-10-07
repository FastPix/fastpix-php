<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class ChaptersResponse
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
     * @var ?bool $isGeneratedChapters
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('isGeneratedChapters')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $isGeneratedChapters = null;

    /**
     * @param  ?string  $mediaId
     * @param  ?bool  $isGeneratedChapters
     * @phpstan-pure
     */
    public function __construct(?string $mediaId = null, ?bool $isGeneratedChapters = null)
    {
        $this->mediaId = $mediaId;
        $this->isGeneratedChapters = $isGeneratedChapters;
    }
}