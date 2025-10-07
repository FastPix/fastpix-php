<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** TrackSubtitlesGenerateRequest - Contains details for generating subtitle tracks for a media file. */
class TrackSubtitlesGenerateRequest
{
    /**
     * The full name of the language in which subtitles will be generated.
     *
     * @var string $languageName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageName')]
    public string $languageName;

    /**
     * Language code for content localization
     *
     * @var LanguageCode $languageCode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageCode')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\LanguageCode')]
    public LanguageCode $languageCode;

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
     * @param  string  $languageName
     * @param  LanguageCode  $languageCode
     * @param  ?array<string, string>  $metadata
     * @phpstan-pure
     */
    public function __construct(string $languageName, LanguageCode $languageCode, ?array $metadata = null)
    {
        $this->languageName = $languageName;
        $this->languageCode = $languageCode;
        $this->metadata = $metadata;
    }
}