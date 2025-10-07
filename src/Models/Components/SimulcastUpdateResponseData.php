<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** SimulcastUpdateResponseData - Displays the result of the request. */
class SimulcastUpdateResponseData
{
    /**
     * When you create the new simulcast, FastPix assign a universal unique identifier which can contain a maximum of 255 characters.
     *
     * @var ?string $simulcastId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('simulcastId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $simulcastId = null;

    /**
     * The RTMP hostname, combined with the application name, is crucial for connecting to third-party live streaming services and transmitting the live stream.
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
     * When the value is set to false, the simulcast will be disabled for the given stream
     *
     * @var ?bool $isEnabled
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('isEnabled')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $isEnabled = null;

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
     * @param  ?string  $simulcastId
     * @param  ?string  $url
     * @param  ?string  $streamKey
     * @param  ?bool  $isEnabled
     * @param  ?array<string, string>  $metadata
     * @phpstan-pure
     */
    public function __construct(?string $simulcastId = null, ?string $url = null, ?string $streamKey = null, ?bool $isEnabled = null, ?array $metadata = null)
    {
        $this->simulcastId = $simulcastId;
        $this->url = $url;
        $this->streamKey = $streamKey;
        $this->isEnabled = $isEnabled;
        $this->metadata = $metadata;
    }
}