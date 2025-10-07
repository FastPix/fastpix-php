<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** DateRange - Date range with start and end dates. */
class DateRange
{
    /**
     *
     * @var ?string $startDate
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('startDate')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $startDate = null;

    /**
     *
     * @var ?string $endDate
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('endDate')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $endDate = null;

    /**
     * @param  ?string  $startDate
     * @param  ?string  $endDate
     * @phpstan-pure
     */
    public function __construct(?string $startDate = null, ?string $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
}