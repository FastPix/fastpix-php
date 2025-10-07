<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class PatchLiveStreamRequest
{
    /**
     * You can search for videos with specific key value pairs using metadata, when you tag a video in "key":"value"s pairs. Dynamic Metadata allows you to define a key that allows any value pair. You can have maximum of 255 characters and upto 10 entries are allowed.
     *
     * @var ?array<string, string> $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string, string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $metadata = null;

    /**
     * In case the software streaming the live, gets disrupted for any reason and gets disconnected from FastPix, the reconnect window specifies the time span FastPix will wait before ending the stream. Before starting the stream, you can set the reconnect window time which is up to 1800 seconds.
     *
     * @var ?int $reconnectWindow
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('reconnectWindow')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $reconnectWindow = null;

    /**
     * @param  ?array<string, string>  $metadata
     * @param  ?int  $reconnectWindow
     * @phpstan-pure
     */
    public function __construct(?array $metadata = null, ?int $reconnectWindow = 60)
    {
        $this->metadata = $metadata;
        $this->reconnectWindow = $reconnectWindow;
    }
}