<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class UpdatedMp4SupportRequest
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
     * @var UpdatedMp4SupportRequestBody $requestBody
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public UpdatedMp4SupportRequestBody $requestBody;

    /**
     * @param  string  $mediaId
     * @param  UpdatedMp4SupportRequestBody  $requestBody
     * @phpstan-pure
     */
    public function __construct(string $mediaId, UpdatedMp4SupportRequestBody $requestBody)
    {
        $this->mediaId = $mediaId;
        $this->requestBody = $requestBody;
    }
}