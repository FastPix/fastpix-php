<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class CreatePlaylistRequest
{
    /**
     * Name of the playlist.
     *
     * @var string $name
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('name')]
    public string $name;

    /**
     * Unique string value assigned by user to the playlist.
     *
     * @var string $referenceId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('referenceId')]
    public string $referenceId;

    /**
     * For a smart playlist metadata is required.
     *
     * @var CreatePlaylistRequestType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\CreatePlaylistRequestType')]
    public CreatePlaylistRequestType $type;

    /**
     * Description for a playlist (Optional).
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
     * Required when playlist type is smart - media created between startDate and endDate of createdDate will be add, similarily updatedDate (Optional)
     *
     * @var ?CreatePlaylistRequestMetadata $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\CreatePlaylistRequestMetadata|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?CreatePlaylistRequestMetadata $metadata = null;

    /**
     * Optional parameter to limit no. of media in a playlist.
     *
     * @var ?int $limit
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('limit')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $limit = null;

    /**
     * @param  string  $name
     * @param  string  $referenceId
     * @param  CreatePlaylistRequestType  $type
     * @param  ?string  $description
     * @param  ?PlaylistOrder  $playOrder
     * @param  ?int  $limit
     * @param  ?CreatePlaylistRequestMetadata  $metadata
     * @phpstan-pure
     */
    public function __construct(string $name, string $referenceId, CreatePlaylistRequestType $type, ?string $description = null, ?PlaylistOrder $playOrder = null, ?CreatePlaylistRequestMetadata $metadata = null, ?int $limit = 1000)
    {
        $this->name = $name;
        $this->referenceId = $referenceId;
        $this->type = $type;
        $this->description = $description;
        $this->playOrder = $playOrder;
        $this->metadata = $metadata;
        $this->limit = $limit;
    }
}