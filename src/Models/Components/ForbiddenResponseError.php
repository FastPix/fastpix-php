<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** ForbiddenResponseError - Displays details about the reasons behind the request's failure. */
class ForbiddenResponseError
{
    /**
     * Forbidden response
     *
     * @var ?int $code
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('code')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $code = null;

    /**
     * A descriptive message providing more details for the error.
     *
     * @var ?string $message
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('message')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $message = null;

    /**
     * @param  ?int  $code
     * @param  ?string  $message
     * @phpstan-pure
     */
    public function __construct(?int $code = null, ?string $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }
}