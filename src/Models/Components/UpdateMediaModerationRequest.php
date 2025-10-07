<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

use FastPix\Sdk\Utils\FastPixMetadata;

class UpdateMediaModerationRequest
{
    /**
     * The media ID for the moderation update
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     * The request body for the moderation update
     *
     * @var UpdateMediaModerationRequestBody $requestBody
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public UpdateMediaModerationRequestBody $requestBody;

    public function __construct(
        string $mediaId,
        UpdateMediaModerationRequestBody $requestBody,
    ) {
        $this->mediaId = $mediaId;
        $this->requestBody = $requestBody;
    }
}
