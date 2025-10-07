<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** InputMediaSettings - Displays the result of the input Media settings. */
class InputMediaSettings
{
    /**
     * Basic access policy for media content
     *
     * @var ?BasicAccessPolicy $mediaPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaPolicy')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\BasicAccessPolicy|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?BasicAccessPolicy $mediaPolicy = null;

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
     * Enables DVR (Digital Video Recorder) functionality for the live stream. When set to true, viewers can pause, rewind, and resume playback during the live broadcast. This allows time-shifted viewing of the stream while it is still ongoing.
     *
     * @var ?bool $enableDvrMode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('enableDvrMode')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $enableDvrMode = null;

    /**
     * Max resolution can be used to control the maximum resolution your media is encoded, stored, and streamed at.
     *
     * @var ?CreateLiveStreamRequestMaxResolution $maxResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxResolution')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\CreateLiveStreamRequestMaxResolution|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?CreateLiveStreamRequestMaxResolution $maxResolution = null;

    /**
     * In case the software streaming the live, gets disrupted for any reason and gets disconnected from FastPix, the reconnect window specifies the time span FastPix will wait before ending the stream. Before starting the stream, you can set the reconnect window time which is up to 1800 seconds.
     *
     * @var ?int $reconnectWindow
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('reconnectWindow')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $reconnectWindow = null;

    /**
     * @param  ?CreateLiveStreamRequestMaxResolution  $maxResolution
     * @param  ?int  $reconnectWindow
     * @param  ?BasicAccessPolicy  $mediaPolicy
     * @param  ?array<string, string>  $metadata
     * @param  ?bool  $enableDvrMode
     * @phpstan-pure
     */
    public function __construct(?BasicAccessPolicy $mediaPolicy = null, ?array $metadata = null, ?bool $enableDvrMode = null, ?CreateLiveStreamRequestMaxResolution $maxResolution = CreateLiveStreamRequestMaxResolution::OneThousandAndEightyp, ?int $reconnectWindow = 60)
    {
        $this->mediaPolicy = $mediaPolicy;
        $this->metadata = $metadata;
        $this->enableDvrMode = $enableDvrMode;
        $this->maxResolution = $maxResolution;
        $this->reconnectWindow = $reconnectWindow;
    }
}