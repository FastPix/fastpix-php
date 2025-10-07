<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** UpdateTrackRequest - Contains details about the track being added to the media file. */
class UpdateTrackRequest
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
     * @param  ?string  $languageCode
     * @param  ?string  $languageName
     * @phpstan-pure
     */
    public function __construct(?string $url = null, ?string $languageCode = null, ?string $languageName = null)
    {
        $this->url = $url;
        $this->languageCode = $languageCode;
        $this->languageName = $languageName;
    }
}