<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class CreateMediaSuccessResponse
{
    /**
     * Demonstrates whether the request is successful or not.
     *
     * @var bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    public bool $success;

    /**
     *
     * @var CreateMediaResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\CreateMediaResponse')]
    public CreateMediaResponse $data;

    /**
     * @param  bool  $success
     * @param  CreateMediaResponse  $data
     * @phpstan-pure
     */
    public function __construct(bool $success, CreateMediaResponse $data)
    {
        $this->success = $success;
        $this->data = $data;
    }
}