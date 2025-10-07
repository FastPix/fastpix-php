<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

use FastPix\Sdk\Utils\FastPixMetadata;

class UpdateMediaNamedEntitiesRequest
{
    /**
     * The media ID for the named entities update
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     * The request body for the named entities update
     *
     * @var UpdateMediaNamedEntitiesRequestBody $requestBody
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public UpdateMediaNamedEntitiesRequestBody $requestBody;

    public function __construct(
        string $mediaId,
        UpdateMediaNamedEntitiesRequestBody $requestBody,
    ) {
        $this->mediaId = $mediaId;
        $this->requestBody = $requestBody;
    }
}
