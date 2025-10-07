<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Utils\FastPixMetadata;
class GetDataViewlistCurrentViewsFilterRequest
{
    /**
     * The dimension to group and breakdown the concurrent viewers data by.
     *
     * This determines how the results will be categorized and aggregated.
     * Choose from geographic, content, technical, or behavioral dimensions.
     *
     *
     * @var ?GetDataViewlistCurrentViewsFilterDimension $dimension
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=dimension')]
    public ?GetDataViewlistCurrentViewsFilterDimension $dimension = null;

    /**
     * Maximum number of results to return. Controls the number of dimension values
     *
     * that will be included in the response. Useful for pagination and performance.
     * Higher limits provide more detailed breakdowns but may impact response time.
     *
     *
     * @var ?int $limit
     */
    #[FastPixMetadata('queryParam:style=form,explode=true,name=limit')]
    public ?int $limit = null;

    /**
     * @param  ?GetDataViewlistCurrentViewsFilterDimension  $dimension
     * @param  ?int  $limit
     * @phpstan-pure
     */
    public function __construct(?GetDataViewlistCurrentViewsFilterDimension $dimension = null, ?int $limit = 10)
    {
        $this->dimension = $dimension;
        $this->limit = $limit;
    }
}