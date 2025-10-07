<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/**
 * Subtitles - Generates subtitle files for audio/video files.
 *
 *
 */
class Subtitles
{
    /**
     * Name of the language in which the subtitles will be generated.
     *
     *
     *
     * @var ?string $languageName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $languageName = null;

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
     * Language codes are concise, standardized symbols that denote languages, utilizing either two or three characters for identification. The language code must be compliant with the BCP 47 standard to ensure compatibility. (for text only).
     *
     *
     *
     * @var ?CreateMediaRequestLanguageCode $languageCode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageCode')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\CreateMediaRequestLanguageCode|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?CreateMediaRequestLanguageCode $languageCode = null;

    /**
     * @param  ?string  $languageName
     * @param  ?array<string, string>  $metadata
     * @param  ?CreateMediaRequestLanguageCode  $languageCode
     * @phpstan-pure
     */
    public function __construct(?string $languageName = null, ?array $metadata = null, ?CreateMediaRequestLanguageCode $languageCode = null)
    {
        $this->languageName = $languageName;
        $this->metadata = $metadata;
        $this->languageCode = $languageCode;
    }
}