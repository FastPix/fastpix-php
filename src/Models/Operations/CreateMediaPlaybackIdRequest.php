<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class CreateMediaPlaybackIdRequest
{
    /**
     * When creating the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters.
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     * Request body for creating playback id for an media
     *
     * @var ?CreateMediaPlaybackIdRequestBody $requestBody
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public ?CreateMediaPlaybackIdRequestBody $requestBody = null;

    /**
     * @param  string  $mediaId
     * @param  ?CreateMediaPlaybackIdRequestBody  $requestBody
     * @phpstan-pure
     */
    public function __construct(string $mediaId, ?CreateMediaPlaybackIdRequestBody $requestBody = null)
    {
        $this->mediaId = $mediaId;
        $this->requestBody = $requestBody;
    }
}