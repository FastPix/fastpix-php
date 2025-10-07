<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class CreatePlaybackIdOfStreamRequest
{
    /**
     * Upon creating a new live stream, FastPix assigns a unique identifier to the stream.
     *
     * @var string $streamId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=streamId')]
    public string $streamId;

    /**
     *
     * @var Components\PlaybackIdRequest $playbackIdRequest
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public Components\PlaybackIdRequest $playbackIdRequest;

    /**
     * @param  string  $streamId
     * @param  Components\PlaybackIdRequest  $playbackIdRequest
     * @phpstan-pure
     */
    public function __construct(string $streamId, Components\PlaybackIdRequest $playbackIdRequest)
    {
        $this->streamId = $streamId;
        $this->playbackIdRequest = $playbackIdRequest;
    }
}