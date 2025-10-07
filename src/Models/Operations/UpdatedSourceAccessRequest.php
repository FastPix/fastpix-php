<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class UpdatedSourceAccessRequest
{
    /**
     * When creating the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters.
     *
     *
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     *
     * @var UpdatedSourceAccessRequestBody $requestBody
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public UpdatedSourceAccessRequestBody $requestBody;

    /**
     * @param  string  $mediaId
     * @param  UpdatedSourceAccessRequestBody  $requestBody
     * @phpstan-pure
     */
    public function __construct(string $mediaId, UpdatedSourceAccessRequestBody $requestBody)
    {
        $this->mediaId = $mediaId;
        $this->requestBody = $requestBody;
    }
}