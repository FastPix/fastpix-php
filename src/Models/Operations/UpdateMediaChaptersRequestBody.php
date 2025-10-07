<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


class UpdateMediaChaptersRequestBody
{
    /**
     * Enable or disable the chapters feature for the media. Set to `true` to enable chapters or `false` to disable.
     *
     *
     *
     * @var bool $chapters
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('chapters')]
    public bool $chapters;

    /**
     * @param  bool  $chapters
     * @phpstan-pure
     */
    public function __construct(bool $chapters)
    {
        $this->chapters = $chapters;
    }
}