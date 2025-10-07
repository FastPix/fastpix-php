<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

class UpdateMediaModerationResponse
{
    public int $statusCode;
    public string $contentType;
    public ?UpdateMediaModerationSuccessResponse $updateMediaModerationSuccessResponse = null;
    public ?\Psr\Http\Message\ResponseInterface $rawResponse = null;

    public function __construct(
        int $statusCode,
        string $contentType,
        ?UpdateMediaModerationSuccessResponse $updateMediaModerationSuccessResponse = null,
        ?\Psr\Http\Message\ResponseInterface $rawResponse = null,
    ) {
        $this->statusCode = $statusCode;
        $this->contentType = $contentType;
        $this->updateMediaModerationSuccessResponse = $updateMediaModerationSuccessResponse;
        $this->rawResponse = $rawResponse;
    }
}