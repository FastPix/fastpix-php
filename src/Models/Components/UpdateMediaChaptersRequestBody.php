<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

class UpdateMediaChaptersRequestBody
{
    /**
     * Array of chapter objects with title, startTime, endTime, and description
     */
    public ?array $chapters = null;

    /**
     * Additional metadata for the chapters update
     */
    public ?array $metadata = null;

    public function __construct(
        ?array $chapters = null,
        ?array $metadata = null,
    ) {
        $this->chapters = $chapters;
        $this->metadata = $metadata;
    }
}
