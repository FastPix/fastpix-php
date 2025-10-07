<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class UpdateMediaTrackRequest
{
    /**
     * When creating the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters.
     *
     * @var string $trackId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=trackId')]
    public string $trackId;

    /**
     * When creating the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters.
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     *
     * @var Components\UpdateTrackRequest $updateTrackRequest
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public Components\UpdateTrackRequest $updateTrackRequest;

    /**
     * @param  string  $trackId
     * @param  string  $mediaId
     * @param  Components\UpdateTrackRequest  $updateTrackRequest
     * @phpstan-pure
     */
    public function __construct(string $trackId, string $mediaId, Components\UpdateTrackRequest $updateTrackRequest)
    {
        $this->trackId = $trackId;
        $this->mediaId = $mediaId;
        $this->updateTrackRequest = $updateTrackRequest;
    }
}