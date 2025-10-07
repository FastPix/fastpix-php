<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

use FastPix\Sdk\Utils\FastPixMetadata;

class UpdateMediaSummaryRequest
{
    /**
     * The media ID for the summary update
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     * The request body for the summary update
     *
     * @var UpdateMediaSummaryRequestBody $requestBody
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public UpdateMediaSummaryRequestBody $requestBody;

    public function __construct(
        string $mediaId,
        UpdateMediaSummaryRequestBody $requestBody,
    ) {
        $this->mediaId = $mediaId;
        $this->requestBody = $requestBody;
    }
}
