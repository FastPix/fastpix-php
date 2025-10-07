<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class PlaylistItem
{
    /**
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     *
     * @var ?string $name
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('name')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $name = null;

    /**
     *
     * @var ?PlaylistItemType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\PlaylistItemType|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?PlaylistItemType $type = null;

    /**
     *
     * @var ?string $referenceId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('referenceId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $referenceId = null;

    /**
     *
     * @var ?\DateTime $createdAt
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('createdAt')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $createdAt = null;

    /**
     *
     * @var ?int $mediaCount
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaCount')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $mediaCount = null;

    /**
     * @param  ?string  $id
     * @param  ?string  $name
     * @param  ?PlaylistItemType  $type
     * @param  ?string  $referenceId
     * @param  ?\DateTime  $createdAt
     * @param  ?int  $mediaCount
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?string $name = null, ?PlaylistItemType $type = null, ?string $referenceId = null, ?\DateTime $createdAt = null, ?int $mediaCount = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->referenceId = $referenceId;
        $this->createdAt = $createdAt;
        $this->mediaCount = $mediaCount;
    }
}