<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** MediaCancelResponse - Response returned when an upload is cancelled. */
class MediaCancelResponse
{
    /**
     * The unique identifier of the cancelled upload.
     *
     * @var ?string $uploadId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('uploadId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $uploadId = null;

    /**
     * Indicates if the upload was a trial.
     *
     * @var ?bool $trial
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('trial')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $trial = null;

    /**
     * The status of the upload after cancellation.
     *
     * @var ?string $status
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('status')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $status = null;

    /**
     * The upload URL (if available) after cancellation.
     *
     * @var ?string $url
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('url')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $url = null;

    /**
     * The timeout value for the upload.
     *
     * @var ?int $timeout
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('timeout')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $timeout = null;

    /**
     * CORS origin allowed for the upload.
     *
     * @var ?string $corsOrigin
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('corsOrigin')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $corsOrigin = null;

    /**
     * The maximum resolution allowed for the upload.
     *
     * @var ?string $maxResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxResolution')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $maxResolution = null;

    /**
     * The access policy for the upload.
     *
     * @var ?string $accessPolicy
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('accessPolicy')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $accessPolicy = null;

    /**
     * You can search for videos with specific key value pairs using metadata, when you tag a video in "key" : "value" pairs. Dynamic Metadata allows you to define a key that allows any value pair. You can have maximum of 255 characters and upto 10 entries are allowed.
     *
     * @var ?array<string, string> $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string, string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $metadata = null;

    /**
     * @param  ?string  $uploadId
     * @param  ?bool  $trial
     * @param  ?string  $status
     * @param  ?string  $url
     * @param  ?int  $timeout
     * @param  ?string  $corsOrigin
     * @param  ?string  $maxResolution
     * @param  ?string  $accessPolicy
     * @param  ?array<string, string>  $metadata
     * @phpstan-pure
     */
    public function __construct(?string $uploadId = null, ?bool $trial = null, ?string $status = null, ?string $url = null, ?int $timeout = null, ?string $corsOrigin = null, ?string $maxResolution = null, ?string $accessPolicy = null, ?array $metadata = null)
    {
        $this->uploadId = $uploadId;
        $this->trial = $trial;
        $this->status = $status;
        $this->url = $url;
        $this->timeout = $timeout;
        $this->corsOrigin = $corsOrigin;
        $this->maxResolution = $maxResolution;
        $this->accessPolicy = $accessPolicy;
        $this->metadata = $metadata;
    }
}