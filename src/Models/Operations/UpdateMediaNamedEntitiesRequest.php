<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class UpdateMediaNamedEntitiesRequest
{
    /**
     * The unique identifier assigned to the media when created. The value should be a valid UUID.
     *
     *
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     *
     * @var UpdateMediaNamedEntitiesRequestBody $requestBody
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public UpdateMediaNamedEntitiesRequestBody $requestBody;

    /**
     * @param  string  $mediaId
     * @param  UpdateMediaNamedEntitiesRequestBody  $requestBody
     * @phpstan-pure
     */
    public function __construct(string $mediaId, UpdateMediaNamedEntitiesRequestBody $requestBody)
    {
        $this->mediaId = $mediaId;
        $this->requestBody = $requestBody;
    }
}