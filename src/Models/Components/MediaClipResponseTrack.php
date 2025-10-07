<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class MediaClipResponseTrack
{
    /**
     * The unique identifier for the media track.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * The type of media track.
     *
     * @var ?MediaClipResponseType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\MediaClipResponseType|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?MediaClipResponseType $type = null;

    /**
     * The width of the video track (applicable to video only).
     *
     * @var ?int $width
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('width')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $width = null;

    /**
     * The height of the video track (applicable to video only).
     *
     * @var ?int $height
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('height')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $height = null;

    /**
     * The current processing status of the track.
     *
     * @var ?string $status
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('status')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $status = null;

    /**
     * The language code of the audio or subtitle track.
     *
     * @var ?string $languageCode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageCode')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $languageCode = null;

    /**
     * The language name of the audio or subtitle track.
     *
     * @var ?string $languageName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $languageName = null;

    /**
     * @param  ?string  $id
     * @param  ?MediaClipResponseType  $type
     * @param  ?int  $width
     * @param  ?int  $height
     * @param  ?string  $status
     * @param  ?string  $languageCode
     * @param  ?string  $languageName
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?MediaClipResponseType $type = null, ?int $width = null, ?int $height = null, ?string $status = null, ?string $languageCode = null, ?string $languageName = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->width = $width;
        $this->height = $height;
        $this->status = $status;
        $this->languageCode = $languageCode;
        $this->languageName = $languageName;
    }
}