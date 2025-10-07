<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** AddTrackResponse - Contains details about the track that was added or updated. */
class AddTrackResponse
{
    /**
     * The unique identifier of the track.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * Specifies the type of track (audio or subtitle).
     *
     * @var ?AddTrackResponseType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\AddTrackResponseType|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?AddTrackResponseType $type = null;

    /**
     * The direct URL of the track file.
     *
     * @var ?string $url
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('url')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $url = null;

    /**
     * The BCP 47 language code representing the track's language.
     *
     * @var ?string $languageCode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageCode')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $languageCode = null;

    /**
     * The full name of the language corresponding to the `languageCode`.
     *
     * @var ?string $languageName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $languageName = null;

    /**
     * @param  ?string  $id
     * @param  ?AddTrackResponseType  $type
     * @param  ?string  $url
     * @param  ?string  $languageCode
     * @param  ?string  $languageName
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?AddTrackResponseType $type = null, ?string $url = null, ?string $languageCode = null, ?string $languageName = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->url = $url;
        $this->languageCode = $languageCode;
        $this->languageName = $languageName;
    }
}