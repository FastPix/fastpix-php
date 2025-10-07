<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class MediaClipResponse
{
    /**
     *
     * @var bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    public bool $success;

    /**
     * $data
     *
     * @var array<MediaClipResponseData> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\MediaClipResponseData>')]
    public array $data;

    /**
     *
     * @var MediaClipResponsePagination $pagination
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pagination')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaClipResponsePagination')]
    public MediaClipResponsePagination $pagination;

    /**
     * @param  bool  $success
     * @param  array<MediaClipResponseData>  $data
     * @param  MediaClipResponsePagination  $pagination
     * @phpstan-pure
     */
    public function __construct(bool $success, array $data, MediaClipResponsePagination $pagination)
    {
        $this->success = $success;
        $this->data = $data;
        $this->pagination = $pagination;
    }
}