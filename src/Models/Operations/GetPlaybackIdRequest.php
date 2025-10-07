<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class GetPlaybackIdRequest
{
    /**
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     *
     * @var string $playbackId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=playbackId')]
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