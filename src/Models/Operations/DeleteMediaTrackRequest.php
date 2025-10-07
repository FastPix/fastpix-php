<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class DeleteMediaTrackRequest
{
    /**
     * When creating the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters.
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     * When creating the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters.
     *
     * @var string $trackId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=trackId')]
    public string $trackId;

    /**
     * @param  string  $mediaId
     * @param  string  $trackId
     * @phpstan-pure
     */
    public function __construct(string $mediaId, string $trackId)
    {
        $this->mediaId = $mediaId;
        $this->trackId = $trackId;
    }
}