<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\SpeakeasyMetadata;
class GetSpecificSimulcastOfStreamRequest
{
    /**
     * Upon creating a new live stream, FastPix assigns a unique identifier to the stream.
     *
     * @var string $streamId
     */
    #[SpeakeasyMetadata('pathParam:style=simple,explode=false,name=streamId')]
    public string $streamId;

    /**
     * When you create the new simulcast, FastPix assign a universal unique identifier which can contain a maximum of 255 characters.
     *
     * @var string $simulcastId
     */
    #[SpeakeasyMetadata('pathParam:style=simple,explode=false,name=simulcastId')]
    public string $simulcastId;

    /**
     * @param  string  $streamId
     * @param  string  $simulcastId
     * @phpstan-pure
     */
    public function __construct(string $streamId, string $simulcastId)
    {
        $this->streamId = $streamId;
        $this->simulcastId = $simulcastId;
    }
}