<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class CompleteLiveStreamRequest
{
    /**
     * Upon creating a new live stream, FastPix assigns a unique identifier to the stream.
     *
     * @var string $streamId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=streamId')]
    public string $streamId;

    /**
     * @param  string  $streamId
     * @phpstan-pure
     */
    public function __construct(string $streamId)
    {
        $this->streamId = $streamId;
    }
}