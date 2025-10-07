<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class ListLiveClipsRequest
{
    /**
     * The stream Id is unique identifier assigned to the live stream.
     *
     * @var string $livestreamId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=livestreamId')]
    public string $livestreamId;

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
     * @param  string  $livestreamId
     * @param  ?int  $limit
     * @param  ?int  $offset
     * @param  ?Components\SortOrder  $orderBy
     * @phpstan-pure
     */
    public function __construct(string $livestreamId, ?int $limit = 10, ?int $offset = 1, ?Components\SortOrder $orderBy = Components\SortOrder::Desc)
    {
        $this->livestreamId = $livestreamId;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderBy = $orderBy;
    }
}