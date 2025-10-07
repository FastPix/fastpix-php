<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/** GetDataViewlistCurrentViewsFilterResponseBody - Successfully retrieved concurrent viewers breakdown by the specified dimension. */
class GetDataViewlistCurrentViewsFilterResponseBody
{
    /**
     * Indicates if the request was successful.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * Breakdown of concurrent viewers by the specified dimension.
     *
     * @var ?array<GetDataViewlistCurrentViewsFilterData> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Operations\GetDataViewlistCurrentViewsFilterData>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $data = null;

    /**
     * Start and end epoch timestamps (milliseconds) for the timespan window.
     *
     * @var ?array<int> $timespan
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('timespan')]
    #[\Speakeasy\Serializer\Annotation\Type('array<int>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $timespan = null;

    /**
     * @param  ?bool  $success
     * @param  ?array<GetDataViewlistCurrentViewsFilterData>  $data
     * @param  ?array<int>  $timespan
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null, ?array $timespan = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->timespan = $timespan;
    }
}