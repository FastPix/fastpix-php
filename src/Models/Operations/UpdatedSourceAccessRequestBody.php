<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


class UpdatedSourceAccessRequestBody
{
    /**
     * The sourceAccess parameter determines whether the original media file is accessible. Set to true to enable access or false to restrict it.
     *
     * @var ?bool $sourceAccess
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('sourceAccess')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $sourceAccess = null;

    /**
     * @param  ?bool  $sourceAccess
     * @phpstan-pure
     */
    public function __construct(?bool $sourceAccess = null)
    {
        $this->sourceAccess = $sourceAccess;
    }
}