<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class UpdatedMediaRequest
{
    /**
     * When creating the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters.
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     *
     * @var UpdatedMediaRequestBody $requestBody
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public UpdatedMediaRequestBody $requestBody;

    /**
     * @param  string  $mediaId
     * @param  UpdatedMediaRequestBody  $requestBody
     * @phpstan-pure
     */
    public function __construct(string $mediaId, UpdatedMediaRequestBody $requestBody)
    {
        $this->mediaId = $mediaId;
        $this->requestBody = $requestBody;
    }
}