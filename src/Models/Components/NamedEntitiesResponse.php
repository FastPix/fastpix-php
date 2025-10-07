<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class NamedEntitiesResponse
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
     * @var ?bool $isGeneratedNamedEntities
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('isGeneratedNamedEntities')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $isGeneratedNamedEntities = null;

    /**
     * @param  ?string  $mediaId
     * @param  ?bool  $isGeneratedNamedEntities
     * @phpstan-pure
     */
    public function __construct(?string $mediaId = null, ?bool $isGeneratedNamedEntities = null)
    {
        $this->mediaId = $mediaId;
        $this->isGeneratedNamedEntities = $isGeneratedNamedEntities;
    }
}