<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class Event
{
    /**
     *
     * @var ?Details $details
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('details')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\Details|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Details $details = null;

    /**
     * Name of the event.
     *
     *
     *
     * @var ?string $eventName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('event_name')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $eventName = null;

    /**
     * The unix epoch timestamp when the event was captured.
     *
     *
     *
     * @var string|int|null $eventTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('event_time')]
    #[\Speakeasy\Serializer\Annotation\Type('string|int|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public string|int|null $eventTime = null;

    /**
     * The unix epoch timestamp which represents the actual time the event has occured.
     *
     *
     *
     * @var ?int $viewerTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewer_time')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $viewerTime = null;

    /**
     * The player_playhead_time represents the current position of the playhead (the point in the video that is being watched) on the video seekbar, measured in milliseconds. This value indicates how far into the video playback has progressed at any given moment.
     *
     *
     *
     * @var ?int $playerPlayheadTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('player_playhead_time')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $playerPlayheadTime = null;

    /**
     * @param  ?Details  $details
     * @param  ?string  $eventName
     * @param  string|int|null  $eventTime
     * @param  ?int  $viewerTime
     * @param  ?int  $playerPlayheadTime
     * @phpstan-pure
     */
    public function __construct(?Details $details = null, ?string $eventName = null, string|int|null $eventTime = null, ?int $viewerTime = null, ?int $playerPlayheadTime = null)
    {
        $this->details = $details;
        $this->eventName = $eventName;
        $this->eventTime = $eventTime;
        $this->viewerTime = $viewerTime;
        $this->playerPlayheadTime = $playerPlayheadTime;
    }
}