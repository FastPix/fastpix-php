<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class DirectUploadResponse
{
    /**
     * $playbackIds
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
     * @var ?array<string, string> $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string, string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $metadata = null;

    /**
     * @param  ?array<PlaybackId>  $playbackIds
     * @param  ?array<string, string>  $metadata
     * @phpstan-pure
     */
    public function __construct(?array $playbackIds = null, ?array $metadata = null)
    {
        $this->playbackIds = $playbackIds;
        $this->metadata = $metadata;
    }
}