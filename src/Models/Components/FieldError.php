<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class FieldError
{
    /**
     * Displays the specific field associated with the error.
     *
     * @var string $field
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('field')]
    public string $field;

    /**
     * Error message for the field
     *
     * @var string $message
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('message')]
    public string $message;

    /**
     * @param  string  $field
     * @param  string  $message
     * @phpstan-pure
     */
    public function __construct(string $field, string $message)
    {
        $this->field = $field;
        $this->message = $message;
    }
}