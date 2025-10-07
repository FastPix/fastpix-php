<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** GetVideoViewDetailsResponseBody - Get a video view by id */
class GetVideoViewDetailsResponseBody
{
    /**
     * It demonstrates whether the request is successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * Displays the result of the request.
     *
     * @var ?Components\Views $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\Views|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\Views $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\Views  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\Views $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}