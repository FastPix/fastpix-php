<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class GetLiveStreamPlaybackIdRequest
{
    /**
     * Upon creating a new live stream, FastPix assigns a unique identifier to the stream.
     *
     * @var string $streamId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=streamId')]
    public string $streamId;

    /**
     * Upon creating a new playbackId, FastPix assigns a unique identifier to the playback.
     *
     * @var string $playbackId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=playbackId')]
    public string $playbackId;

    /**
     * @param  string  $streamId
     * @param  string  $playbackId
     * @phpstan-pure
     */
    public function __construct(string $streamId, string $playbackId)
    {
        $this->streamId = $streamId;
        $this->playbackId = $playbackId;
    }
}