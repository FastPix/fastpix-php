<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

class DirectUploadResponse
{
    public int $statusCode;
    public string $contentType;
    public ?DirectUploadSuccessResponse $directUploadSuccessResponse = null;
    public ?\Psr\Http\Message\ResponseInterface $rawResponse = null;

    public function __construct(
        int $statusCode,
        string $contentType,
        ?DirectUploadSuccessResponse $directUploadSuccessResponse = null,
        ?\Psr\Http\Message\ResponseInterface $rawResponse = null,
    ) {
        $this->statusCode = $statusCode;
        $this->contentType = $contentType;
        $this->directUploadSuccessResponse = $directUploadSuccessResponse;
        $this->rawResponse = $rawResponse;
    }
}
