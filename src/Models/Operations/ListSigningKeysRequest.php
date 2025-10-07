<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class ListSigningKeysRequest
{
    /**
     * Limit specifies the maximum number of items to display per page.
     *
     * @var ?float $limit
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=limit')]
    public ?float $limit = null;

    /**
     * It is used for pagination, indicating the starting point for fetching data.  
     *
     * @var ?float $offset
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=offset')]
    public ?float $offset = null;

    /**
     * @param  ?float  $limit
     * @param  ?float  $offset
     * @phpstan-pure
     */
    public function __construct(?float $limit = 10, ?float $offset = 1)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }
}