<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class BrowserNameDimensiondetails
{
    /**
     * The specific metric value calculated based on the applied filters.
     *
     * @var string $value
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('value')]
    public string $value;

    /**
     * The count of viewers.
     *
     * @var int $count
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('count')]
    public int $count;

    /**
     * The count of unique viewers who interacted with the content.
     *
     * @var ?int $uniqueCount
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('uniqueCount')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $uniqueCount = null;

    /**
     * @param  string  $value
     * @param  int  $count
     * @param  ?int  $uniqueCount
     * @phpstan-pure
     */
    public function __construct(string $value, int $count, ?int $uniqueCount = null)
    {
        $this->value = $value;
        $this->count = $count;
        $this->uniqueCount = $uniqueCount;
    }
}