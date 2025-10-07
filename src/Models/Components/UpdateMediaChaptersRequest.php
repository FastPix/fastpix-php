<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

use FastPix\Sdk\Utils\FastPixMetadata;

class UpdateMediaChaptersRequest
{
    /**
     * The media ID for the chapters update
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     * The request body for the chapters update
     *
     * @var UpdateMediaChaptersRequestBody $requestBody
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public UpdateMediaChaptersRequestBody $requestBody;

    public function __construct(
        string $mediaId,
        UpdateMediaChaptersRequestBody $requestBody,
    ) {
        $this->mediaId = $mediaId;
        $this->requestBody = $requestBody;
    }
}
