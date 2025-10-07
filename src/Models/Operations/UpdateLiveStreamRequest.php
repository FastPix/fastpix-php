<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class UpdateLiveStreamRequest
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
     * @var Components\PatchLiveStreamRequest $patchLiveStreamRequest
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public Components\PatchLiveStreamRequest $patchLiveStreamRequest;

    /**
     * @param  string  $streamId
     * @param  Components\PatchLiveStreamRequest  $patchLiveStreamRequest
     * @phpstan-pure
     */
    public function __construct(string $streamId, Components\PatchLiveStreamRequest $patchLiveStreamRequest)
    {
        $this->streamId = $streamId;
        $this->patchLiveStreamRequest = $patchLiveStreamRequest;
    }
}