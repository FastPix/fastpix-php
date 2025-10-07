<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** GetStreamsResponse - Displays the result of the request. */
class GetStreamsResponse
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
     * @var ?array<GetCreateLiveStreamResponseDTO> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\GetCreateLiveStreamResponseDTO>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $data = null;

    /**
     * Pagination organizes content into pages for better readability and navigation.
     *
     * @var ?LiveStreamPagination $pagination
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pagination')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\LiveStreamPagination|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?LiveStreamPagination $pagination = null;

    /**
     * @param  ?bool  $success
     * @param  ?array<GetCreateLiveStreamResponseDTO>  $data
     * @param  ?LiveStreamPagination  $pagination
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null, ?LiveStreamPagination $pagination = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->pagination = $pagination;
    }
}