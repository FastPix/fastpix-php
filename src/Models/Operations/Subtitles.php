<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/**
 * Subtitles - Generates subtitle files for audio/video files.
 *
 *
 */
class Subtitles
{
    /**
     * Name of the language for the subtitles.
     *
     * @var ?string $languageName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $languageName = null;

    /**
     * Tag a video in "key" : "value" pairs for searchable metadata. Maximum 10 entries, 255 characters each.
     *
     * @var ?array<string, string> $metadata
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('metadata')]
    #[\Speakeasy\Serializer\Annotation\Type('array<string, string>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $metadata = null;

    /**
     * Language codes (BCP 47 compliant) used for text files.
     *
     *
     *
     * @var ?LanguageCode $languageCode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageCode')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\LanguageCode|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?LanguageCode $languageCode = null;

    /**
     * @param  ?string  $languageName
     * @param  ?array<string, string>  $metadata
     * @param  ?LanguageCode  $languageCode
     * @phpstan-pure
     */
    public function __construct(?string $languageName = null, ?array $metadata = null, ?LanguageCode $languageCode = null)
    {
        $this->languageName = $languageName;
        $this->metadata = $metadata;
        $this->languageCode = $languageCode;
    }
}