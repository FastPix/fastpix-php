<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** UpdateMediaChaptersResponseBody - Media details updated successfully with the chapters feature enabled or disabled */
class UpdateMediaChaptersResponseBody
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
     * @var ?Components\ChaptersResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\ChaptersResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\ChaptersResponse $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\ChaptersResponse  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\ChaptersResponse $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}