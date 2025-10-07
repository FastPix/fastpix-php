<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

class UpdateMediaChaptersResponse
{
    public int $statusCode;
    public string $contentType;
    public ?UpdateMediaChaptersSuccessResponse $updateMediaChaptersSuccessResponse = null;
    public ?\Psr\Http\Message\ResponseInterface $rawResponse = null;

    public function __construct(
        int $statusCode,
        string $contentType,
        ?UpdateMediaChaptersSuccessResponse $updateMediaChaptersSuccessResponse = null,
        ?\Psr\Http\Message\ResponseInterface $rawResponse = null,
    ) {
        $this->statusCode = $statusCode;
        $this->contentType = $contentType;
        $this->updateMediaChaptersSuccessResponse = $updateMediaChaptersSuccessResponse;
        $this->rawResponse = $rawResponse;
    }
}