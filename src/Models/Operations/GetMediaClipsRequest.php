<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class GetMediaClipsRequest
{
    /**
     * The unique identifier of the source media.
     *
     * @var string $sourceMediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=sourceMediaId')]
    public string $sourceMediaId;

    /**
     * Offset determines the starting point for data retrieval within a paginated list.
     *
     * @var ?int $offset
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=offset')]
    public ?int $offset = null;

    /**
     * The number of media clips to retrieve per request.
     *
     * @var ?int $limit
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=limit')]
    public ?int $limit = null;

    /**
     * The values in the list can be arranged in two ways DESC (Descending) or ASC (Ascending).
     *
     * @var ?Components\SortOrder $orderBy
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=orderBy')]
    public ?Components\SortOrder $orderBy = null;

    /**
     * @param  string  $sourceMediaId
     * @param  ?int  $offset
     * @param  ?int  $limit
     * @param  ?Components\SortOrder  $orderBy
     * @phpstan-pure
     */
    public function __construct(string $sourceMediaId, ?int $offset = 1, ?int $limit = 10, ?Components\SortOrder $orderBy = Components\SortOrder::Desc)
    {
        $this->sourceMediaId = $sourceMediaId;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->orderBy = $orderBy;
    }
}