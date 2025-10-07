<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class Details
{
    /**
     * The player_source_bitrate represents the bitrate of the video stream that is being played, measured in bits per second (bps). This value indicates the quality of the video being streamed, with higher bitrates typically corresponding to better video quality but requiring more bandwidth.
     *
     *
     *
     * @var ?int $playerSourceBitrate
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('player_source_bitrate')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $playerSourceBitrate = null;

    /**
     * The player_source_codec represents the video or audio codec being used to decode and play the media. A codec is a technology used to compress and decompress digital media files, enabling efficient transmission and storage while maintaining quality.
     *
     *
     *
     * @var ?string $playerSourceCodec
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('player_source_codec')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $playerSourceCodec = null;

    /**
     * The player_source_height refers to the vertical resolution of the video being played, measured in pixels. This value represents the height dimension of the video frame and is part of the overall resolution of the video (e.g., 1920x1080, where the height is 1080 pixels).
     *
     *
     *
     * @var ?int $playerSourceHeight
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerSourceHeight')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $playerSourceHeight = null;

    /**
     * The player_source_width refers to the horizontal resolution of the video being played, measured in pixels. This value represents the width dimension of the video frame and is part of the overall video resolution (e.g., 1920x1080, where the width is 1920 pixels).
     *
     *
     *
     * @var ?int $playerSourceWidth
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerSourceWidth')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $playerSourceWidth = null;

    /**
     * @param  ?int  $playerSourceBitrate
     * @param  ?string  $playerSourceCodec
     * @param  ?int  $playerSourceHeight
     * @param  ?int  $playerSourceWidth
     * @phpstan-pure
     */
    public function __construct(?int $playerSourceBitrate = null, ?string $playerSourceCodec = null, ?int $playerSourceHeight = null, ?int $playerSourceWidth = null)
    {
        $this->playerSourceBitrate = $playerSourceBitrate;
        $this->playerSourceCodec = $playerSourceCodec;
        $this->playerSourceHeight = $playerSourceHeight;
        $this->playerSourceWidth = $playerSourceWidth;
    }
}