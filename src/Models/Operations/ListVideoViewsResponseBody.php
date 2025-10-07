<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** ListVideoViewsResponseBody - Get the list of Views */
class ListVideoViewsResponseBody
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
     * @var ?array<Components\ViewsList> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\ViewsList>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $data = null;

    /**
     * Pagination organizes content into pages for better readability and navigation.
     *
     * @var ?Components\DataPagination $pagination
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pagination')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\DataPagination|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\DataPagination $pagination = null;

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
     * @param  ?array<Components\ViewsList>  $data
     * @param  ?Components\DataPagination  $pagination
     * @param  ?array<int>  $timespan
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null, ?Components\DataPagination $pagination = null, ?array $timespan = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->pagination = $pagination;
        $this->timespan = $timespan;
    }
}