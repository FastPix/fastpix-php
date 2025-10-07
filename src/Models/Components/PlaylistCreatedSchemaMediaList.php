<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class PlaylistCreatedSchemaMediaList
{
    /**
     * timestamp of media creation in the workspace
     *
     * @var ?\DateTime $createdAt
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('createdAt')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $createdAt = null;

    /**
     * duration of the media in hh:mm:ss format
     *
     * @var ?string $duration
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('duration')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $duration = null;

    /**
     * unique identifier of the media
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * The source resolution of the media
     *
     * @var ?string $sourceResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('sourceResolution')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $sourceResolution = null;

    /**
     * The status of the video in the workspace. Only media which are in ready status are added into the playlist
     *
     * @var ?string $status
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('status')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $status = null;

    /**
     * Thumbnail to the particular media
     *
     * @var ?string $thumbnail
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('thumbnail')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $thumbnail = null;

    /**
     * @param  ?\DateTime  $createdAt
     * @param  ?string  $duration
     * @param  ?string  $id
     * @param  ?string  $sourceResolution
     * @param  ?string  $status
     * @param  ?string  $thumbnail
     * @phpstan-pure
     */
    public function __construct(?\DateTime $createdAt = null, ?string $duration = null, ?string $id = null, ?string $sourceResolution = null, ?string $status = null, ?string $thumbnail = null)
    {
        $this->createdAt = $createdAt;
        $this->duration = $duration;
        $this->id = $id;
        $this->sourceResolution = $sourceResolution;
        $this->status = $status;
        $this->thumbnail = $thumbnail;
    }
}