<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class SimulcastRequest
{
    /**
     * The RTMPS hostname, combined with the application name, is crucial for connecting to third-party live streaming services and transmitting the live stream.
     *
     * @var ?string $url
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('url')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $url = null;

    /**
     * A unique stream key is generated for streaming, allowing the user to start streaming on any third-party platform using this key.
     *
     * @var ?string $streamKey
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('streamKey')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $streamKey = null;

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
     * @param  ?string  $url
     * @param  ?string  $streamKey
     * @param  ?array<string, string>  $metadata
     * @phpstan-pure
     */
    public function __construct(?string $url = null, ?string $streamKey = null, ?array $metadata = null)
    {
        $this->url = $url;
        $this->streamKey = $streamKey;
        $this->metadata = $metadata;
    }
}