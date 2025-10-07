<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** ViewNotFoundError - Returns the problem that has occured */
class ViewNotFoundError
{
    /**
     * An error code indicating the type of the error.
     *
     * @var ?float $code
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('code')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $code = null;

    /**
     * A descriptive message providing more details for the error.
     *
     * @var ?string $message
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('message')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $message = null;

    /**
     * @param  ?float  $code
     * @param  ?string  $message
     * @phpstan-pure
     */
    public function __construct(?float $code = null, ?string $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }
}