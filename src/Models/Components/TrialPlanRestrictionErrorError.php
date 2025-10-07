<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** TrialPlanRestrictionErrorError - Contains details explaining why the request failed. */
class TrialPlanRestrictionErrorError
{
    /**
     * HTTP status code indicating the nature of the error.
     *
     * @var ?float $code
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('code')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $code = null;

    /**
     * A short message summarizing the error.
     *
     * @var ?string $message
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('message')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $message = null;

    /**
     * A detailed explanation of the error indicating that the operation is restricted under the trial plan. This typically occurs when certain features are gated for paid users only.
     *
     *
     *
     * @var ?string $description
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('description')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $description = null;

    /**
     * @param  ?float  $code
     * @param  ?string  $message
     * @param  ?string  $description
     * @phpstan-pure
     */
    public function __construct(?float $code = null, ?string $message = null, ?string $description = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->description = $description;
    }
}