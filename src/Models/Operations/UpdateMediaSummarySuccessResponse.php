<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components\Media;

class UpdateMediaSummarySuccessResponse
{
    public ?Media $data = null;

    public function __construct(?Media $data = null)
    {
        $this->data = $data;
    }
}
