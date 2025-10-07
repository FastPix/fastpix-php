<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class ListMediaRequest
{
    /**
     * Limit specifies the maximum number of items to display per page.
     *
     * @var ?int $limit
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=limit')]
    public ?int $limit = null;

    /**
     * Offset determines the starting point for data retrieval within a paginated list.
     *
     * @var ?int $offset
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=offset')]
    public ?int $offset = null;

    /**
     * The values in the list can be arranged in two ways: DESC (Descending) or ASC (Ascending).
     *
     * @var ?Components\SortOrder $orderBy
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=orderBy')]
    public ?Components\SortOrder $orderBy = null;

    /**
     * @param  ?int  $limit
     * @param  ?int  $offset
     * @param  ?Components\SortOrder  $orderBy
     * @phpstan-pure
     */
    public function __construct(?int $limit = 10, ?int $offset = 1, ?Components\SortOrder $orderBy = Components\SortOrder::Desc)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderBy = $orderBy;
    }
}