<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

class UpdateMediaSummaryRequestBody
{
    /**
     * The updated summary text for the media
     */
    public ?string $summary = null;

    /**
     * The updated description for the media
     */
    public ?string $description = null;

    /**
     * Additional metadata for the summary update
     */
    public ?array $metadata = null;

    public function __construct(
        ?string $summary = null,
        ?string $description = null,
        ?array $metadata = null,
    ) {
        $this->summary = $summary;
        $this->description = $description;
        $this->metadata = $metadata;
    }
}
