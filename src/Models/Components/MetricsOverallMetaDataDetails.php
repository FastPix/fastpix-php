<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** MetricsOverallMetaDataDetails - Metadata that has to be paased for metric calculations. */
class MetricsOverallMetaDataDetails
{
    /**
     * defines the field or dimension on which the aggregation is to be   applied.
     *
     * @var ?string $aggregation
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('aggregation')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $aggregation = null;

    /**
     * @param  ?string  $aggregation
     * @phpstan-pure
     */
    public function __construct(?string $aggregation = null)
    {
        $this->aggregation = $aggregation;
    }
}