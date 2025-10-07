<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class SummaryResponse
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
     * @var ?bool $isGeneratedSummary
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('isGeneratedSummary')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $isGeneratedSummary = null;

    /**
     * @param  ?string  $mediaId
     * @param  ?bool  $isGeneratedSummary
     * @phpstan-pure
     */
    public function __construct(?string $mediaId = null, ?bool $isGeneratedSummary = null)
    {
        $this->mediaId = $mediaId;
        $this->isGeneratedSummary = $isGeneratedSummary;
    }
}