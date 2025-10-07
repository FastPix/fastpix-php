<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** PlaylistCreatedSchema - Displays the result of the request. */
class PlaylistCreatedSchema
{
    /**
     * Upon creating a new play,ist, FastPix assigns a unique identifier to the playlist.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * The name to the playlist set by the user.
     *
     * @var ?string $name
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('name')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $name = null;

    /**
     * Unique string value assigned by user to the playlist.
     *
     * @var ?string $referenceId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('referenceId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $referenceId = null;

    /**
     * Type will be either smart or manual, as sent in the request body.
     *
     * @var ?PlaylistCreatedSchemaType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\PlaylistCreatedSchemaType|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?PlaylistCreatedSchemaType $type = null;

    /**
     * The description to the playlist set by the user.
     *
     * @var ?string $description
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('description')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $description = null;

    /**
     * Determines the insertion order of media into playlist.
     *
     * @var ?PlaylistOrder $playOrder
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playOrder')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\PlaylistOrder|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?PlaylistOrder $playOrder = null;

    /**
     * date range filter used when creating the smart playlist
     *
     * @var ?PlaylistCreatedSchemaMetadata $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\PlaylistCreatedSchemaMetadata|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?PlaylistCreatedSchemaMetadata $metadata = null;

    /**
     * $mediaList
     *
     * @var ?array<PlaylistCreatedSchemaMediaList> $mediaList
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaList')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\PlaylistCreatedSchemaMediaList>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $mediaList = null;

    /**
     * Id of the workspace
     *
     * @var ?string $workspaceId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('workspaceId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $workspaceId = null;

    /**
     * Timestamp of playlist creation.
     *
     * @var ?\DateTime $createdAt
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('createdAt')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $createdAt = null;

    /**
     * Playlist's most recent update timestamp.
     *
     * @var ?\DateTime $updatedAt
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('updatedAt')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $updatedAt = null;

    /**
     * No. of media present in the playlist
     *
     * @var ?int $mediaCount
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaCount')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $mediaCount = null;

    /**
     * @param  ?string  $id
     * @param  ?string  $name
     * @param  ?string  $referenceId
     * @param  ?PlaylistCreatedSchemaType  $type
     * @param  ?string  $description
     * @param  ?PlaylistOrder  $playOrder
     * @param  ?PlaylistCreatedSchemaMetadata  $metadata
     * @param  ?array<PlaylistCreatedSchemaMediaList>  $mediaList
     * @param  ?string  $workspaceId
     * @param  ?\DateTime  $createdAt
     * @param  ?\DateTime  $updatedAt
     * @param  ?int  $mediaCount
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?string $name = null, ?string $referenceId = null, ?PlaylistCreatedSchemaType $type = null, ?string $description = null, ?PlaylistOrder $playOrder = null, ?PlaylistCreatedSchemaMetadata $metadata = null, ?array $mediaList = null, ?string $workspaceId = null, ?\DateTime $createdAt = null, ?\DateTime $updatedAt = null, ?int $mediaCount = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->referenceId = $referenceId;
        $this->type = $type;
        $this->description = $description;
        $this->playOrder = $playOrder;
        $this->metadata = $metadata;
        $this->mediaList = $mediaList;
        $this->workspaceId = $workspaceId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->mediaCount = $mediaCount;
    }
}