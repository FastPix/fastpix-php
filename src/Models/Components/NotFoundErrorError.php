<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class NotFoundErrorError
{
    /**
     *
     * @var ?int $code
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('code')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $code = null;

    /**
     *
     * @var ?string $message
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('message')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $message = null;

    /**
     *
     * @var ?string $description
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('description')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $description = null;

    /**
     * @param  ?int  $code
     * @param  ?string  $message
     * @param  ?string  $description
     * @phpstan-pure
     */
    public function __construct(?int $code = null, ?string $message = null, ?string $description = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->description = $description;
    }
}