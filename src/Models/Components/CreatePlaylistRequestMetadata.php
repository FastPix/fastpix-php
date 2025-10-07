<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** CreatePlaylistRequestMetadata - Required when playlist type is smart - media created between startDate and endDate of createdDate will be add, similarily updatedDate (Optional) */
class CreatePlaylistRequestMetadata
{
    /**
     * Date range with start and end dates.
     *
     * @var ?DateRange $createdDate
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('createdDate')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\DateRange|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?DateRange $createdDate = null;

    /**
     * Date range with start and end dates.
     *
     * @var ?DateRange $updatedDate
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('updatedDate')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\DateRange|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?DateRange $updatedDate = null;

    /**
     * @param  ?DateRange  $createdDate
     * @param  ?DateRange  $updatedDate
     * @phpstan-pure
     */
    public function __construct(?DateRange $createdDate = null, ?DateRange $updatedDate = null)
    {
        $this->createdDate = $createdDate;
        $this->updatedDate = $updatedDate;
    }
}