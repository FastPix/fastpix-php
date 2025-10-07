<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/** ListDimensionsResponseBody - Get the list of Views */
class ListDimensionsResponseBody
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
     * @var ?array<string> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?array<string>  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}