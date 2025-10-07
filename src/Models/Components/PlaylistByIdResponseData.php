<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class PlaylistByIdResponseData
{
    /**
     * The unique id of the playlist
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * The name of the playlist set by the user
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
     * type of the playlist, when it was created
     *
     * @var ?PlaylistByIdResponseType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\PlaylistByIdResponseType|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?PlaylistByIdResponseType $type = null;

    /**
     * Description of the playlist set by the user.
     *
     * @var ?string $description
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('description')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $description = null;

    /**
     * $mediaList
     *
     * @var ?array<PlaylistByIdResponseMediaList> $mediaList
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaList')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\PlaylistByIdResponseMediaList>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $mediaList = null;

    /**
     * The unique id of the workspace in which the playlist is present.
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
     * @param  ?PlaylistByIdResponseType  $type
     * @param  ?string  $description
     * @param  ?array<PlaylistByIdResponseMediaList>  $mediaList
     * @param  ?string  $workspaceId
     * @param  ?\DateTime  $createdAt
     * @param  ?\DateTime  $updatedAt
     * @param  ?int  $mediaCount
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?string $name = null, ?string $referenceId = null, ?PlaylistByIdResponseType $type = null, ?string $description = null, ?array $mediaList = null, ?string $workspaceId = null, ?\DateTime $createdAt = null, ?\DateTime $updatedAt = null, ?int $mediaCount = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->referenceId = $referenceId;
        $this->type = $type;
        $this->description = $description;
        $this->mediaList = $mediaList;
        $this->workspaceId = $workspaceId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->mediaCount = $mediaCount;
    }
}