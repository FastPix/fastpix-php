<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** LiveStreamPagination - Pagination organizes content into pages for better readability and navigation. */
class LiveStreamPagination
{
    /**
     * It gives the total number of media assets that are accessible overall.
     *
     * @var ?int $totalRecords
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('totalRecords')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $totalRecords = null;

    /**
     * Determines the current point for data retrieval within a paginated list.
     *
     * @var ?int $currentOffset
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('currentOffset')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $currentOffset = null;

    /**
     * The offset count is expressed as total records by limit.
     *
     * @var ?int $offsetCount
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('offsetCount')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $offsetCount = null;

    /**
     * @param  ?int  $totalRecords
     * @param  ?int  $currentOffset
     * @param  ?int  $offsetCount
     * @phpstan-pure
     */
    public function __construct(?int $totalRecords = null, ?int $currentOffset = null, ?int $offsetCount = null)
    {
        $this->totalRecords = $totalRecords;
        $this->currentOffset = $currentOffset;
        $this->offsetCount = $offsetCount;
    }
}