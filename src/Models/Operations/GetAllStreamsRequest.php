<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class GetAllStreamsRequest
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
     * The list of value can be order in two ways DESC (Descending) or ASC (Ascending). In case not specified, by default it will be DESC.
     *
     * @var ?OrderBy $orderBy
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=orderBy')]
    public ?OrderBy $orderBy = null;

    /**
     * @param  ?int  $limit
     * @param  ?int  $offset
     * @param  ?OrderBy  $orderBy
     * @phpstan-pure
     */
    public function __construct(?int $limit = 10, ?int $offset = 1, ?OrderBy $orderBy = OrderBy::Desc)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderBy = $orderBy;
    }
}