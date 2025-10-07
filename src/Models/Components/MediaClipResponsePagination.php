<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class MediaClipResponsePagination
{
    /**
     * Total number of records available.
     *
     * @var ?int $totalRecords
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('totalRecords')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $totalRecords = null;

    /**
     * The starting offset of the current result set.
     *
     * @var ?int $currentOffset
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('currentOffset')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $currentOffset = null;

    /**
     * The number of items returned in the current response.
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