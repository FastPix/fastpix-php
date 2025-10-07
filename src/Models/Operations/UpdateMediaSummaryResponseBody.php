<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** UpdateMediaSummaryResponseBody - Media details updated successfully with the generated summary */
class UpdateMediaSummaryResponseBody
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
     * @var ?Components\SummaryResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\SummaryResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\SummaryResponse $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\SummaryResponse  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\SummaryResponse $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}