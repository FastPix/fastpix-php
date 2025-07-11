<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

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
     * @var ?Pagination $pagination
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pagination')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\Pagination|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Pagination $pagination = null;

    /**
     * @param  ?bool  $success
     * @param  ?array<GetCreateLiveStreamResponseDTO>  $data
     * @param  ?Pagination  $pagination
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null, ?Pagination $pagination = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->pagination = $pagination;
    }
}