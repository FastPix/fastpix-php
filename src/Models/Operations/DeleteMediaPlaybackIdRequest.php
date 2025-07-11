<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\SpeakeasyMetadata;
class DeleteMediaPlaybackIdRequest
{
    /**
     * Return the universal unique identifier for media which can contain a maximum of 255 characters.
     *
     * @var string $mediaId
     */
    #[SpeakeasyMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     * Return the universal unique identifier for playbacks  which can contain a maximum of 255 characters. 
     *
     * @var string $playbackId
     */
    #[SpeakeasyMetadata('queryParam:style=form,explode=true,name=playbackId')]
    public string $playbackId;

    /**
     * @param  string  $mediaId
     * @param  string  $playbackId
     * @phpstan-pure
     */
    public function __construct(string $mediaId, string $playbackId)
    {
        $this->mediaId = $mediaId;
        $this->playbackId = $playbackId;
    }
}