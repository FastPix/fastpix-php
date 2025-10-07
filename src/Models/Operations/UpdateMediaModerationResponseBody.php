<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** UpdateMediaModerationResponseBody - Media details updated successfully with the named entity extraction feature enabled or disabled */
class UpdateMediaModerationResponseBody
{
    /**
     * Indicates if the request was successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     *
     * @var ?Components\ModerationResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\ModerationResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\ModerationResponse $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\ModerationResponse  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\ModerationResponse $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}