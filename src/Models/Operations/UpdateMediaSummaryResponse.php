<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

class UpdateMediaSummaryResponse
{
    public int $statusCode;
    public string $contentType;
    public ?UpdateMediaSummarySuccessResponse $updateMediaSummarySuccessResponse = null;
    public ?\Psr\Http\Message\ResponseInterface $rawResponse = null;

    public function __construct(
        int $statusCode,
        string $contentType,
        ?UpdateMediaSummarySuccessResponse $updateMediaSummarySuccessResponse = null,
        ?\Psr\Http\Message\ResponseInterface $rawResponse = null,
    ) {
        $this->statusCode = $statusCode;
        $this->contentType = $contentType;
        $this->updateMediaSummarySuccessResponse = $updateMediaSummarySuccessResponse;
        $this->rawResponse = $rawResponse;
    }
}