<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class GetDrmConfigurationByIdRequest
{
    /**
     * The unique identifier of the DRM configuration.
     *
     * @var string $drmConfigurationId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=drmConfigurationId')]
    public string $drmConfigurationId;

    /**
     * @param  string  $drmConfigurationId
     * @phpstan-pure
     */
    public function __construct(string $drmConfigurationId)
    {
        $this->drmConfigurationId = $drmConfigurationId;
    }
}