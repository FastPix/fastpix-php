<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class TopErrorDetails
{
    /**
     * views affected by the specific errors.
     *
     * @var int|float|null $percentage
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('percentage')]
    #[\Speakeasy\Serializer\Annotation\Type('int|float|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public int|float|null $percentage = null;

    /**
     * percentage of unique viewers affected by the specific error.
     *
     * @var int|float|null $uniqueViewersEffectedPercentage
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('uniqueViewersEffectedPercentage')]
    #[\Speakeasy\Serializer\Annotation\Type('int|float|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public int|float|null $uniqueViewersEffectedPercentage = null;

    /**
     * Information about the specific error.
     *
     * @var ?string $notes
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('notes')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $notes = null;

    /**
     * error message or description.
     *
     * @var ?string $message
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('message')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $message = null;

    /**
     * The timestamp of when the error was last observed.
     *
     * @var ?string $lastSeen
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('lastSeen')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $lastSeen = null;

    /**
     * unique identifier for the specific error.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * description of the specific error.
     *
     * @var ?string $description
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('description')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $description = null;

    /**
     * Number of occurrences of the specific error.
     *
     * @var ?int $count
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('count')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $count = null;

    /**
     * Error code associated with the specific error.
     *
     * @var ?string $code
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('code')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $code = null;

    /**
     * @param  int|float|null  $percentage
     * @param  int|float|null  $uniqueViewersEffectedPercentage
     * @param  ?string  $notes
     * @param  ?string  $message
     * @param  ?string  $lastSeen
     * @param  ?string  $id
     * @param  ?string  $description
     * @param  ?int  $count
     * @param  ?string  $code
     * @phpstan-pure
     */
    public function __construct(int|float|null $percentage = null, int|float|null $uniqueViewersEffectedPercentage = null, ?string $notes = null, ?string $message = null, ?string $lastSeen = null, ?string $id = null, ?string $description = null, ?int $count = null, ?string $code = null)
    {
        $this->percentage = $percentage;
        $this->uniqueViewersEffectedPercentage = $uniqueViewersEffectedPercentage;
        $this->notes = $notes;
        $this->message = $message;
        $this->lastSeen = $lastSeen;
        $this->id = $id;
        $this->description = $description;
        $this->count = $count;
        $this->code = $code;
    }
}