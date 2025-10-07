<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/** ListErrorsResponseBody - Get filter/ dimension value details by dimension name. */
class ListErrorsResponseBody
{
    /**
     * It demonstrates whether the request is successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * Displays the result of the request.
     *
     * @var ?ListErrorsData $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\ListErrorsData|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?ListErrorsData $data = null;

    /**
     * The timeframe from and to details displayed in the form of unix epoch timestamps.
     *
     *
     *
     * @var ?array<int> $timespan
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('timespan')]
    #[\Speakeasy\Serializer\Annotation\Type('array<int>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $timespan = null;

    /**
     * @param  ?bool  $success
     * @param  ?ListErrorsData  $data
     * @param  ?array<int>  $timespan
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?ListErrorsData $data = null, ?array $timespan = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->timespan = $timespan;
    }
}