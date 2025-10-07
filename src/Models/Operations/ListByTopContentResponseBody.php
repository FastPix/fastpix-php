<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** ListByTopContentResponseBody - Get the list of Views */
class ListByTopContentResponseBody
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
     * @var ?array<Components\ViewsByTopContentDetails> $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\ViewsByTopContentDetails>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?array<Components\ViewsByTopContentDetails>  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?array $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}