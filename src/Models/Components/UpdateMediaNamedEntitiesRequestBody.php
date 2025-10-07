<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

class UpdateMediaNamedEntitiesRequestBody
{
    /**
     * Array of named entity objects with type, value, startTime, endTime, and confidence
     */
    public ?array $entities = null;

    /**
     * Additional metadata for the named entities update
     */
    public ?array $metadata = null;

    public function __construct(
        ?array $entities = null,
        ?array $metadata = null,
    ) {
        $this->entities = $entities;
        $this->metadata = $metadata;
    }
}
