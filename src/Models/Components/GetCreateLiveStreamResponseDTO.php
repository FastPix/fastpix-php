<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** GetCreateLiveStreamResponseDTO - Displays the result of the request. */
class GetCreateLiveStreamResponseDTO
{
    /**
     * Upon creating a new live stream, FastPix assigns a unique identifier to the stream.
     *
     * @var ?string $streamId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('streamId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $streamId = null;

    /**
     * A unique stream key is generated for streaming, allowing the user to start streaming on any third-party platform using this key.
     *
     * @var ?string $streamKey
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('streamKey')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $streamKey = null;

    /**
     * A secret used for securing the SRT stream. This ensures that only authorized users can access the stream.
     *
     * @var ?string $srtSecret
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('srtSecret')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $srtSecret = null;

    /**
     * FastPix allows for a to trial the live stream for free. The duration of trial streams is five minutes. After five minutes of activity, the trial stream is turned off, and the recorded asset is removed after a day.
     *
     * @var ?bool $trial
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('trial')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $trial = null;

    /**
     * The current live stream status can be one of four values:Idle, Preparing, Active or Disabled.The Idle status signifies that there isn't a broadcast in progress.The preparing status indicates that the stream is getting prepared. while, the Active status indicates that a broadcast is currently in progress. The Disabled status means that no more RTMPS streams can be published.
     *
     * @var ?string $status
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('status')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $status = null;

    /**
     * Max resolution can be used to control the maximum resolution your media is encoded, stored, and streamed at.
     *
     * @var ?string $maxResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxResolution')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $maxResolution = null;

    /**
     * The maximum duration in seconds that a live stream can have before it ends the stream.
     *
     * @var ?int $maxDuration
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxDuration')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $maxDuration = null;

    /**
     * It is the moment when the stream was created Time the media was generated, defined as a localDateTime (UTC Time).
     *
     * @var ?\DateTime $createdAt
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('createdAt')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?\DateTime $createdAt = null;

    /**
     * When set to true, the livestream will be recorded and stored for later viewing purposes. If set to false, the livestream will not be recorded.
     *
     * @var ?bool $enableRecording
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('enableRecording')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $enableRecording = null;

    /**
     * Determines whether the recorded stream should be publicly accessible or private in Live to VOD (Video on Demand).
     *
     * @var ?string $mediaPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaPolicy')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $mediaPolicy = null;

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
     * A collection of Playback ID objects utilized for crafting HLS playback urls.
     *
     * @var ?array<PlaybackIdResponse> $playbackIds
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playbackIds')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\PlaybackIdResponse>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $playbackIds = null;

    /**
     * A set of media IDs created after the livestream ends. (live to VOD)
     *
     * @var ?array<string> $mediaIds
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaIds')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $mediaIds = null;

    /**
     * This object contains the livestream playback response details for SRT Protocol
     *
     * @var ?SrtPlaybackResponse $srtPlaybackResponse
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('srtPlaybackResponse')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\SrtPlaybackResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?SrtPlaybackResponse $srtPlaybackResponse = null;

    /**
     * In case the software streaming the live, gets disrupted for any reason and gets disconnected from FastPix, the reconnect window specifies the time span FastPix will wait before ending the stream. Before starting the stream, you can set the reconnect window time which is up to 1800 seconds.
     *
     * @var ?int $reconnectWindow
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('reconnectWindow')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $reconnectWindow = null;

    /**
     * @param  ?string  $streamId
     * @param  ?string  $streamKey
     * @param  ?string  $srtSecret
     * @param  ?bool  $trial
     * @param  ?string  $status
     * @param  ?string  $maxResolution
     * @param  ?int  $maxDuration
     * @param  ?\DateTime  $createdAt
     * @param  ?int  $reconnectWindow
     * @param  ?bool  $enableRecording
     * @param  ?string  $mediaPolicy
     * @param  ?array<string, string>  $metadata
     * @param  ?bool  $enableDvrMode
     * @param  ?array<PlaybackIdResponse>  $playbackIds
     * @param  ?array<string>  $mediaIds
     * @param  ?SrtPlaybackResponse  $srtPlaybackResponse
     * @phpstan-pure
     */
    public function __construct(?string $streamId = null, ?string $streamKey = null, ?string $srtSecret = null, ?bool $trial = null, ?string $status = null, ?string $maxResolution = null, ?int $maxDuration = null, ?\DateTime $createdAt = null, ?bool $enableRecording = null, ?string $mediaPolicy = null, ?array $metadata = null, ?bool $enableDvrMode = null, ?array $playbackIds = null, ?array $mediaIds = null, ?SrtPlaybackResponse $srtPlaybackResponse = null, ?int $reconnectWindow = 60)
    {
        $this->streamId = $streamId;
        $this->streamKey = $streamKey;
        $this->srtSecret = $srtSecret;
        $this->trial = $trial;
        $this->status = $status;
        $this->maxResolution = $maxResolution;
        $this->maxDuration = $maxDuration;
        $this->createdAt = $createdAt;
        $this->enableRecording = $enableRecording;
        $this->mediaPolicy = $mediaPolicy;
        $this->metadata = $metadata;
        $this->enableDvrMode = $enableDvrMode;
        $this->playbackIds = $playbackIds;
        $this->mediaIds = $mediaIds;
        $this->srtPlaybackResponse = $srtPlaybackResponse;
        $this->reconnectWindow = $reconnectWindow;
    }
}