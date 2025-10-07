<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

class UpdateMediaModerationRequestBody
{
    /**
     * Moderation settings configuration
     */
    public ?array $moderationSettings = null;

    /**
     * Safety settings configuration
     */
    public ?array $safetySettings = null;

    /**
     * Additional metadata for the moderation update
     */
    public ?array $metadata = null;

    public function __construct(
        ?array $moderationSettings = null,
        ?array $safetySettings = null,
        ?array $metadata = null,
    ) {
        $this->moderationSettings = $moderationSettings;
        $this->safetySettings = $safetySettings;
        $this->metadata = $metadata;
    }
}
