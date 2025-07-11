<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** PushMediaSettings - Configuration settings for media upload. */
class PushMediaSettings
{
    /**
     * Determines if access to the streamed content is kept private or available to all.
     *
     * @var DirectUploadVideoMediaAccessPolicy $accessPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessPolicy')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\DirectUploadVideoMediaAccessPolicy')]
    public DirectUploadVideoMediaAccessPolicy $accessPolicy;

    /**
     * Start time indicates where encoding should begin within the video file, in seconds.
     *
     * @var ?float $startTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('startTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $startTime = null;

    /**
     * End time indicates where encoding should end within the video file, in seconds.
     *
     * @var ?float $endTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('endTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $endTime = null;

    /**
     * $inputs
     *
     * @var ?array<Components\VideoInput|Components\WatermarkInput|Components\AudioInput|Components\SubtitleInput> $inputs
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('inputs')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\VideoInput|\FastPix\Sdk\Models\Components\WatermarkInput|\FastPix\Sdk\Models\Components\AudioInput|\FastPix\Sdk\Models\Components\SubtitleInput>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $inputs = null;

    /**
     * Tag a video in "key" : "value" pairs for searchable metadata. Maximum 10 entries, 255 characters each.
     *
     * @var ?DirectUploadVideoMediaMetadata $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\DirectUploadVideoMediaMetadata|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?DirectUploadVideoMediaMetadata $metadata = null;

    /**
     * Generates subtitle files for audio/video files.
     *
     *
     *
     * @var ?Subtitles $subtitles
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('subtitles')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\Subtitles|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Subtitles $subtitles = null;

    /**
     * The sourceAccess parameter determines whether the original media file is accessible. Set to true to enable access or false to restrict it
     *
     * @var ?bool $sourceAccess
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('sourceAccess')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $sourceAccess = null;

    /**
     * Generates MP4 video up to 4K ("capped_4k"), m4a audio only ("audioOnly"), or both for offline viewing.
     *
     *
     *
     * @var ?Mp4Support $mp4Support
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mp4Support')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\Mp4Support|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Mp4Support $mp4Support = null;

    /**
     *
     * @var ?Summary $summary
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('summary')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\Summary|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Summary $summary = null;

    /**
     * Enable or disable the chapters feature for the media. Set to `true` to enable chapters or `false` to disable.
     *
     *
     *
     * @var ?bool $chapters
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('chapters')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $chapters = null;

    /**
     * Enable or disable named entity extraction. Set to `true` to enable or `false` to disable.
     *
     *
     *
     * @var ?bool $namedEntities
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('namedEntities')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $namedEntities = null;

    /**
     *
     * @var ?Moderation $moderation
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('moderation')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\Moderation|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Moderation $moderation = null;

    /**
     *
     * @var ?DirectUploadVideoMediaAccessRestrictions $accessRestrictions
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessRestrictions')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\DirectUploadVideoMediaAccessRestrictions|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?DirectUploadVideoMediaAccessRestrictions $accessRestrictions = null;

    /**
     * Enhance the quality and volume of the audio track. This is available for pre-recorded content only.
     *
     *
     *
     * @var ?bool $optimizeAudio
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('optimizeAudio')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $optimizeAudio = null;

    /**
     * Determines the highest quality resolution available.
     *
     *
     *
     * @var ?MaxResolution $maxResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxResolution')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\MaxResolution|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?MaxResolution $maxResolution = null;

    /**
     * @param  DirectUploadVideoMediaAccessPolicy  $accessPolicy
     * @param  ?float  $startTime
     * @param  ?float  $endTime
     * @param  ?array<Components\VideoInput|Components\WatermarkInput|Components\AudioInput|Components\SubtitleInput>  $inputs
     * @param  ?DirectUploadVideoMediaMetadata  $metadata
     * @param  ?Subtitles  $subtitles
     * @param  ?bool  $optimizeAudio
     * @param  ?MaxResolution  $maxResolution
     * @param  ?bool  $sourceAccess
     * @param  ?Mp4Support  $mp4Support
     * @param  ?Summary  $summary
     * @param  ?bool  $chapters
     * @param  ?bool  $namedEntities
     * @param  ?Moderation  $moderation
     * @param  ?DirectUploadVideoMediaAccessRestrictions  $accessRestrictions
     * @phpstan-pure
     */
    public function __construct(DirectUploadVideoMediaAccessPolicy $accessPolicy, ?float $startTime = null, ?float $endTime = null, ?array $inputs = null, ?DirectUploadVideoMediaMetadata $metadata = null, ?Subtitles $subtitles = null, ?bool $sourceAccess = null, ?Mp4Support $mp4Support = null, ?Summary $summary = null, ?bool $chapters = null, ?bool $namedEntities = null, ?Moderation $moderation = null, ?DirectUploadVideoMediaAccessRestrictions $accessRestrictions = null, ?bool $optimizeAudio = true, ?MaxResolution $maxResolution = MaxResolution::OneThousandAndEightyp)
    {
        $this->accessPolicy = $accessPolicy;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->inputs = $inputs;
        $this->metadata = $metadata;
        $this->subtitles = $subtitles;
        $this->sourceAccess = $sourceAccess;
        $this->mp4Support = $mp4Support;
        $this->summary = $summary;
        $this->chapters = $chapters;
        $this->namedEntities = $namedEntities;
        $this->moderation = $moderation;
        $this->accessRestrictions = $accessRestrictions;
        $this->optimizeAudio = $optimizeAudio;
        $this->maxResolution = $maxResolution;
    }
}