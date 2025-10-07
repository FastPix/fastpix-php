<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class CreateSimulcastOfStreamRequest
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
     * @var Components\SimulcastRequest $simulcastRequest
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public Components\SimulcastRequest $simulcastRequest;

    /**
     * @param  string  $streamId
     * @param  Components\SimulcastRequest  $simulcastRequest
     * @phpstan-pure
     */
    public function __construct(string $streamId, Components\SimulcastRequest $simulcastRequest)
    {
        $this->streamId = $streamId;
        $this->simulcastRequest = $simulcastRequest;
    }
}