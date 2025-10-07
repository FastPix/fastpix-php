<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class DeleteMediaRequest
{
    /**
     * When creating the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters.
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     * @param  string  $mediaId
     * @phpstan-pure
     */
    public function __construct(string $mediaId)
    {
        $this->mediaId = $mediaId;
    }
}