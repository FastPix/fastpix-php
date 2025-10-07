<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

class UpdateMediaNamedEntitiesResponse
{
    public int $statusCode;
    public string $contentType;
    public ?UpdateMediaNamedEntitiesSuccessResponse $updateMediaNamedEntitiesSuccessResponse = null;
    public ?\Psr\Http\Message\ResponseInterface $rawResponse = null;

    public function __construct(
        int $statusCode,
        string $contentType,
        ?UpdateMediaNamedEntitiesSuccessResponse $updateMediaNamedEntitiesSuccessResponse = null,
        ?\Psr\Http\Message\ResponseInterface $rawResponse = null,
    ) {
        $this->statusCode = $statusCode;
        $this->contentType = $contentType;
        $this->updateMediaNamedEntitiesSuccessResponse = $updateMediaNamedEntitiesSuccessResponse;
        $this->rawResponse = $rawResponse;
    }
}