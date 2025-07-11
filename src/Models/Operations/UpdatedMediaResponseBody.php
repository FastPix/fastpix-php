<?php

/**
 * Code generated by Speakeasy (https://speakeasy.com). DO NOT EDIT.
 */

declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
/** UpdatedMediaResponseBody - Media details updated successfully */
class UpdatedMediaResponseBody
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
     *
     * @var ?Components\Media $data
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('data')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\Media|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?Components\Media $data = null;

    /**
     * @param  ?bool  $success
     * @param  ?Components\Media  $data
     * @phpstan-pure
     */
    public function __construct(?bool $success = null, ?Components\Media $data = null)
    {
        $this->success = $success;
        $this->data = $data;
    }
}