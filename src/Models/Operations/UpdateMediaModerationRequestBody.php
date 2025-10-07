<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


class UpdateMediaModerationRequestBody
{
    /**
     *
     * @var ?UpdateMediaModerationModeration $moderation
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('moderation')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\UpdateMediaModerationModeration|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?UpdateMediaModerationModeration $moderation = null;

    /**
     * @param  ?UpdateMediaModerationModeration  $moderation
     * @phpstan-pure
     */
    public function __construct(?UpdateMediaModerationModeration $moderation = null)
    {
        $this->moderation = $moderation;
    }
}