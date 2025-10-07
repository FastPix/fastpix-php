<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class RetrieveMediaInputInfoRequest
{
    /**
     * Pass the list of the input objects used to create the media, along with applied settings.
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