<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** UpdateMediaNamedEntitiesResponseBody - Media details updated successfully with the named entity extraction feature enabled or disabled */
class UpdateMediaNamedEntitiesResponseBody
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
     * @var ?Components\NamedEntitiesResponse $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\NamedEntitiesResponse|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\NamedEntitiesResponse $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\NamedEntitiesResponse  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\NamedEntitiesResponse $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}