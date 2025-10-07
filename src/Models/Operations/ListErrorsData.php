<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** ListErrorsData - Displays the result of the request. */
class ListErrorsData
{
    /**
     * Retrieves a list of errors that have occurred in the system.
     *
     * @var ?array<Components\ErrorDetails> $errors
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('errors')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\ErrorDetails>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $errors = null;

    /**
     * Retrieves a list of errors that have occurred most frequently in the system, ranked by their count of occurrences.
     *
     * @var ?array<Components\TopErrorDetails> $topErrors
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('topErrors')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\TopErrorDetails>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $topErrors = null;

    /**
     * @param  ?array<Components\ErrorDetails>  $errors
     * @param  ?array<Components\TopErrorDetails>  $topErrors
     * @phpstan-pure
     */
    public function __construct(?array $errors = null, ?array $topErrors = null)
    {
        $this->errors = $errors;
        $this->topErrors = $topErrors;
    }
}