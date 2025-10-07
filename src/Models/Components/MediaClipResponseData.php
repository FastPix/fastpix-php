<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class MediaClipResponseData
{
    /**
     * A video thumbnail that acts as a preview image for the video.
     *
     * @var ?string $thumbnail
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('thumbnail')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $thumbnail = null;

    /**
     * The unique identifier assigned to the media by FastPix.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * The ID of the original source media.
     *
     * @var ?string $sourceMediaId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('sourceMediaId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $sourceMediaId = null;

    /**
     * The unique identifier for the workspace associated with the media.
     *
     * @var ?string $workspaceId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('workspaceId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $workspaceId = null;

    /**
     * Tag a video in "key" : "value" pairs for searchable metadata. Maximum 10 entries, 255 characters each.
     *
     * @var ?array<string, string> $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string, string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $metadata = null;

    /**
     * The maximum resolution specified for the media.
     *
     * @var ?MediaClipResponseMaxResolution $maxResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxResolution')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaClipResponseMaxResolution|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?MediaClipResponseMaxResolution $maxResolution = null;

    /**
     * The actual resolution of the uploaded media.
     *
     * @var ?MediaClipResponseSourceResolution $sourceResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('sourceResolution')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaClipResponseSourceResolution|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?MediaClipResponseSourceResolution $sourceResolution = null;

    /**
     * The current processing status of the media.
     *
     * @var ?Status $status
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('status')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\Status|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Status $status = null;

    /**
     * Indicates whether the original media file is accessible.
     *
     * @var ?bool $sourceAccess
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('sourceAccess')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $sourceAccess = null;

    /**
     * $playbackIds
     *
     * @var ?array<MediaClipResponsePlaybackId> $playbackIds
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playbackIds')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\MediaClipResponsePlaybackId>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $playbackIds = null;

    /**
     * $tracks
     *
     * @var ?array<MediaClipResponseTrack> $tracks
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('tracks')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\MediaClipResponseTrack>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $tracks = null;

    /**
     * Generated subtitle tracks associated with the media.
     *
     * @var ?array<GeneratedSubtitle> $generatedSubtitles
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('generatedSubtitles')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\GeneratedSubtitle>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $generatedSubtitles = null;

    /**
     * Indicates whether the media contains only audio.
     *
     * @var ?bool $isAudioOnly
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('isAudioOnly')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $isAudioOnly = null;

    /**
     * Indicates whether subtitles are available for the media.
     *
     * @var ?bool $subtitleAvailable
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('subtitleAvailable')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $subtitleAvailable = null;

    /**
     * The total duration of the media.
     *
     * @var ?string $duration
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('duration')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $duration = null;

    /**
     * The aspect ratio of the media.
     *
     * @var ?string $aspectRatio
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('aspectRatio')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $aspectRatio = null;

    /**
     * Timestamp of when the media was created.
     *
     * @var ?\DateTime $createdAt
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('createdAt')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $createdAt = null;

    /**
     * Timestamp of when the media was last updated.
     *
     * @var ?\DateTime $updatedAt
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('updatedAt')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $updatedAt = null;

    /**
     * @param  ?string  $thumbnail
     * @param  ?string  $id
     * @param  ?string  $sourceMediaId
     * @param  ?string  $workspaceId
     * @param  ?array<string, string>  $metadata
     * @param  ?MediaClipResponseMaxResolution  $maxResolution
     * @param  ?MediaClipResponseSourceResolution  $sourceResolution
     * @param  ?Status  $status
     * @param  ?bool  $sourceAccess
     * @param  ?array<MediaClipResponsePlaybackId>  $playbackIds
     * @param  ?array<MediaClipResponseTrack>  $tracks
     * @param  ?array<GeneratedSubtitle>  $generatedSubtitles
     * @param  ?bool  $isAudioOnly
     * @param  ?bool  $subtitleAvailable
     * @param  ?string  $duration
     * @param  ?string  $aspectRatio
     * @param  ?\DateTime  $createdAt
     * @param  ?\DateTime  $updatedAt
     * @phpstan-pure
     */
    public function __construct(?string $thumbnail = null, ?string $id = null, ?string $sourceMediaId = null, ?string $workspaceId = null, ?array $metadata = null, ?MediaClipResponseMaxResolution $maxResolution = null, ?MediaClipResponseSourceResolution $sourceResolution = null, ?Status $status = null, ?bool $sourceAccess = null, ?array $playbackIds = null, ?array $tracks = null, ?array $generatedSubtitles = null, ?bool $isAudioOnly = null, ?bool $subtitleAvailable = null, ?string $duration = null, ?string $aspectRatio = null, ?\DateTime $createdAt = null, ?\DateTime $updatedAt = null)
    {
        $this->thumbnail = $thumbnail;
        $this->id = $id;
        $this->sourceMediaId = $sourceMediaId;
        $this->workspaceId = $workspaceId;
        $this->metadata = $metadata;
        $this->maxResolution = $maxResolution;
        $this->sourceResolution = $sourceResolution;
        $this->status = $status;
        $this->sourceAccess = $sourceAccess;
        $this->playbackIds = $playbackIds;
        $this->tracks = $tracks;
        $this->generatedSubtitles = $generatedSubtitles;
        $this->isAudioOnly = $isAudioOnly;
        $this->subtitleAvailable = $subtitleAvailable;
        $this->duration = $duration;
        $this->aspectRatio = $aspectRatio;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}