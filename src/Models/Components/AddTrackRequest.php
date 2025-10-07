<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** AddTrackRequest - Contains details about the track being added to the media file. */
class AddTrackRequest
{
    /**
     * The direct URL of the track file. It should point to a valid audio or subtitle file.
     *
     * @var ?string $url
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('url')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $url = null;

    /**
     * Specifies the type of track being added. It can be either `audio` or `subtitle`.
     *
     * @var ?AddTrackRequestType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\AddTrackRequestType|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?AddTrackRequestType $type = null;

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
     * @param  ?string  $url
     * @param  ?AddTrackRequestType  $type
     * @param  ?string  $languageCode
     * @param  ?string  $languageName
     * @phpstan-pure
     */
    public function __construct(?string $url = null, ?AddTrackRequestType $type = null, ?string $languageCode = null, ?string $languageName = null)
    {
        $this->url = $url;
        $this->type = $type;
        $this->languageCode = $languageCode;
        $this->languageName = $languageName;
    }
}