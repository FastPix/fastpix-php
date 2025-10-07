<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

class DirectUploadRequest
{
    /**
     * The file content to upload
     */
    public ?string $file = null;

    /**
     * The name of the file
     */
    public ?string $fileName = null;

    /**
     * The MIME type of the file
     */
    public ?string $contentType = null;

    /**
     * Additional metadata for the upload
     */
    public ?array $metadata = null;

    /**
     * Access policy for the uploaded media
     */
    public ?string $accessPolicy = null;

    public function __construct(
        ?string $file = null,
        ?string $fileName = null,
        ?string $contentType = null,
        ?array $metadata = null,
        ?string $accessPolicy = null,
    ) {
        $this->file = $file;
        $this->fileName = $fileName;
        $this->contentType = $contentType;
        $this->metadata = $metadata;
        $this->accessPolicy = $accessPolicy;
    }
}
