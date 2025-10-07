<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class GetVideoViewDetailsRequest
{
    /**
     * Pass View id
     *
     * @var string $viewId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=viewId')]
    public string $viewId;

    /**
     * @param  string  $viewId
     * @phpstan-pure
     */
    public function __construct(string $viewId)
    {
        $this->viewId = $viewId;
    }
}