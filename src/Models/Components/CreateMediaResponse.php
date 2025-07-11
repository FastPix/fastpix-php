<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class CreateMediaResponse
{
    /**
     * The Media is assigned a universal unique identifier, which can contain a maximum of 255 characters.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * Determines the media's status, which can be one of the possible values.
     *
     * @var ?string $status
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('status')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $status = null;

    /**
     * Time the media was created, defined as a localDateTime (UTC Time).
     *
     * @var ?\DateTime $createdAt
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('createdAt')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $createdAt = null;

    /**
     * Time the media was updated, defined as a localDateTime (UTC Time).
     *
     * @var ?\DateTime $updatedAt
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('updatedAt')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $updatedAt = null;

    /**
     * A collection of Playback ID objects utilized for crafting HLS playback URLs.
     *
     * @var ?array<PlaybackId> $playbackIds
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playbackIds')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\PlaybackId>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $playbackIds = null;

    /**
     * You can search for videos with specific key value pairs using metadata, when you tag a video in "key" : "value" pairs. Dynamic Metadata allows you to define a key that allows any value pair. You can have maximum of 255 characters and upto 10 entries are allowed.
     *
     * @var ?CreateMediaResponseMetadata $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\CreateMediaResponseMetadata|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?CreateMediaResponseMetadata $metadata = null;

    /**
     * FastPix allows for a free trial. Create as many media files as you like during the trial period. Remember, each clip can only be 10 seconds long and will be deleted after 24 hours. Also, all trial content will have the FastPix logo watermark.
     *
     *
     *
     * @var ?bool $trial
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('trial')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $trial = null;

    /**
     * The maximum resolution tier determines the highest quality your media will be available in.
     *
     * @var ?CreateMediaResponseMaxResolution $maxResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxResolution')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\CreateMediaResponseMaxResolution|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?CreateMediaResponseMaxResolution $maxResolution = null;

    /**
     * @param  ?string  $id
     * @param  ?bool  $trial
     * @param  ?string  $status
     * @param  ?\DateTime  $createdAt
     * @param  ?\DateTime  $updatedAt
     * @param  ?array<PlaybackId>  $playbackIds
     * @param  ?CreateMediaResponseMetadata  $metadata
     * @param  ?CreateMediaResponseMaxResolution  $maxResolution
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?string $status = null, ?\DateTime $createdAt = null, ?\DateTime $updatedAt = null, ?array $playbackIds = null, ?CreateMediaResponseMetadata $metadata = null, ?bool $trial = true, ?CreateMediaResponseMaxResolution $maxResolution = CreateMediaResponseMaxResolution::OneThousandAndEightyp)
    {
        $this->id = $id;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->playbackIds = $playbackIds;
        $this->metadata = $metadata;
        $this->trial = $trial;
        $this->maxResolution = $maxResolution;
    }
}