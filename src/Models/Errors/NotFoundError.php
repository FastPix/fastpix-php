<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils;
class NotFoundError
{
    /**
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     *
     * @var ?Components\NotFoundErrorError $error
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('error')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\NotFoundErrorError|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\NotFoundErrorError $error = null;

    /**
     * Raw HTTP response; suitable for custom response parsing
     *
     * @var ?\Psr\Http\Message\ResponseInterface $rawResponse
     */
    #[\Speakeasy\Serializer\Annotation\Exclude]

    public ?\Psr\Http\Message\ResponseInterface $rawResponse = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\NotFoundErrorError  $error
     * @param  ?\Psr\Http\Message\ResponseInterface  $rawResponse
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\NotFoundErrorError $error = null, ?\Psr\Http\Message\ResponseInterface $rawResponse = null)
    {
        $this->success = $success;
        $this->error = $error;
        $this->rawResponse = $rawResponse;
    }

    public function toException(): NotFoundErrorThrowable
    {
        $serializer = Utils\JSON::createSerializer();
        $message = $serializer->serialize($this, 'json');
        $code = -1;

        return new NotFoundErrorThrowable($message, (int) $code, $this);
    }
}