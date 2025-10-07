<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** DataPagination - Pagination organizes content into pages for better readability and navigation. */
class DataPagination
{
    /**
     * The total number of records retrieved within the timeframe.
     *
     *
     *
     * @var ?int $totalRecords
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('totalRecords')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $totalRecords = null;

    /**
     * The current offset value. 
     *
     *
     * Default: 1
     *
     *
     * @var ?int $currentOffset
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('currentOffset')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $currentOffset = null;

    /**
     * The total number of offsets based on limit.
     *
     *
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