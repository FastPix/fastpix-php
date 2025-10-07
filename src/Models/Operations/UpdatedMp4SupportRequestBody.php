<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


class UpdatedMp4SupportRequestBody
{
    /**
     * Determines the type of MP4 support for the media.   - **none**: Disables MP4 support.   - **capped_4k**: Enables MP4 downloads with resolutions up to 4K.   - **audioOnly**: Provides an MP4 stream containing only the audio.   - **audioOnly,capped_4k**: Enables both MP4 video downloads (up to 4K) and an audio-only stream.  
     *
     * @var ?UpdatedMp4SupportMp4Support $mp4Support
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mp4Support')]
    #[\Speakeasy\Serializer\Annotation\Type('\FastPix\Sdk\Models\Operations\UpdatedMp4SupportMp4Support|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?UpdatedMp4SupportMp4Support $mp4Support = null;

    /**
     * @param  ?UpdatedMp4SupportMp4Support  $mp4Support
     * @phpstan-pure
     */
    public function __construct(?UpdatedMp4SupportMp4Support $mp4Support = null)
    {
        $this->mp4Support = $mp4Support;
    }
}