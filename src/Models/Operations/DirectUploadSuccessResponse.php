<?php

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components\DirectUpload;

class DirectUploadSuccessResponse
{
    public ?DirectUpload $data = null;

    public function __construct(?DirectUpload $data = null)
    {
        $this->data = $data;
    }
}
