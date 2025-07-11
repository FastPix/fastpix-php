<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** DirectUploadVideoMediaResponseBody - Direct upload created successfully */
class DirectUploadVideoMediaResponseBody
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
     * Displays the result of the request.
     *
     * @var ?Components\DirectUpload $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\DirectUpload|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\DirectUpload $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\DirectUpload  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\DirectUpload $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}