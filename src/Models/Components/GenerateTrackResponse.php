<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** GenerateTrackResponse - Represents the response for a successfully generated subtitle track. */
class GenerateTrackResponse
{
    /**
     * A unique identifier for the generated track.
     *
     * @var ?string $id
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('id')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $id = null;

    /**
     * The type of track generated ("subtitle").
     *
     * @var ?GenerateTrackResponseType $type
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('type')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\GenerateTrackResponseType|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?GenerateTrackResponseType $type = null;

    /**
     * The BCP 47 language code representing the language of the generated track.
     *
     *
     *
     * @var ?GenerateTrackResponseLanguageCode $languageCode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('languageCode')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Components\GenerateTrackResponseLanguageCode|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?GenerateTrackResponseLanguageCode $languageCode = null;

    /**
     * The full name of the language for the generated track.
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
     * @param  ?string  $id
     * @param  ?GenerateTrackResponseType  $type
     * @param  ?GenerateTrackResponseLanguageCode  $languageCode
     * @param  ?string  $languageName
     * @param  ?array<string, string>  $metadata
     * @phpstan-pure
     */
    public function __construct(?string $id = null, ?GenerateTrackResponseType $type = null, ?GenerateTrackResponseLanguageCode $languageCode = null, ?string $languageName = null, ?array $metadata = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->languageCode = $languageCode;
        $this->languageName = $languageName;
        $this->metadata = $metadata;
    }
}