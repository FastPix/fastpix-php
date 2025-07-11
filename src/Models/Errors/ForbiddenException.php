<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

use FastPix\Sdk\Utils;
class ForbiddenException
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
     * @var ?ForbiddenError $error
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('error')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Errors\ForbiddenError|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?ForbiddenError $error = null;

    /**
     * @param  ?bool  $success
     * @param  ?ForbiddenError  $error
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?ForbiddenError $error = null)
    {
        $this->success = $success;
        $this->error = $error;
    }

    public function toException(): ForbiddenExceptionThrowable
    {
        $serializer = Utils\JSON::createSerializer();
        $message = $serializer->serialize($this, 'json');
        $code = -1;

        return new ForbiddenExceptionThrowable($message, (int) $code, $this);
    }
}