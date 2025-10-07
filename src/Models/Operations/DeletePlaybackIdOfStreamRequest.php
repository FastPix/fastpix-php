<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class DeletePlaybackIdOfStreamRequest
{
    /**
     * Upon creating a new live stream, FastPix assigns a unique identifier to the stream.
     *
     * @var string $streamId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=streamId')]
    public string $streamId;

    /**
     * Unique identifier for the playbackId
     *
     * @var string $playbackId
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=playbackId')]
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