<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** SubtitleInput - Generates subtitle files for audio/video files. */
class SubtitleInput
{
    /**
     * Defines the type of input.
     *
     *
     *
     * @var string $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    public string $type;

    /**
     * The direct URL of the subtitle file.
     *
     * @var string $url
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('url')]
    public string $url;

    /**
     * Name of the language in which the subtitles will be generated.
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
     * @param  string  $type
     * @param  string  $url
     * @param  string  $languageName
     * @param  LanguageCode  $languageCode
     * @phpstan-pure
     */
    public function __construct(string $type, string $url, string $languageName, LanguageCode $languageCode)
    {
        $this->type = $type;
        $this->url = $url;
        $this->languageName = $languageName;
        $this->languageCode = $languageCode;
    }
}