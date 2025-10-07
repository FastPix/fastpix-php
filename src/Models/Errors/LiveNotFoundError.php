<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils;
class LiveNotFoundError
{
    /**
     * Demonstrates whether the request is successful or not.
     *
     * @var ?bool $success
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('success')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $success = null;

    /**
     * Displays details about the reasons behind the request's failure.
     *
     * @var ?Components\LiveNotFoundErrorError $error
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('error')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\LiveNotFoundErrorError|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\LiveNotFoundErrorError $error = null;

    /**
     * Raw HTTP response; suitable for custom response parsing
     *
     * @var ?\Psr\Http\Message\ResponseInterface $rawResponse
     */
    #[\Speakeasy\Serializer\Annotation\Exclude]

    public ?\Psr\Http\Message\ResponseInterface $rawResponse = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\LiveNotFoundErrorError  $error
     * @param  ?\Psr\Http\Message\ResponseInterface  $rawResponse
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\LiveNotFoundErrorError $error = null, ?\Psr\Http\Message\ResponseInterface $rawResponse = null)
    {
        $this->success = $success;
        $this->error = $error;
        $this->rawResponse = $rawResponse;
    }

    public function toException(): LiveNotFoundErrorThrowable
    {
        $serializer = Utils\JSON::createSerializer();
        $message = $serializer->serialize($this, 'json');
        $code = -1;

        return new LiveNotFoundErrorThrowable($message, (int) $code, $this);
    }
}