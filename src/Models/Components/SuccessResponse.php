<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class SuccessResponse
{
    /**
     * Demonstrates whether the request is successful or not.
     *
     * @var bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    public bool $success;

    /**
     * Array of response data
     *
     * @var array<SuccessResponseData> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\SuccessResponseData>')]
    public array $data;

    /**
     * @param  bool  $success
     * @param  array<SuccessResponseData>  $data
     * @phpstan-pure
     */
    public function __construct(bool $success, array $data)
    {
        $this->success = $success;
        $this->data = $data;
    }
}