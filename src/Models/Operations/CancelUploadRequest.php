<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class CancelUploadRequest
{
    /**
     * When uploading the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters.
     *
     * @var string $uploadId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=uploadId')]
    public string $uploadId;

    /**
     * @param  string  $uploadId
     * @phpstan-pure
     */
    public function __construct(string $uploadId)
    {
        $this->uploadId = $uploadId;
    }
}