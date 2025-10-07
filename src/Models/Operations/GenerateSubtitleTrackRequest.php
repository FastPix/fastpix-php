<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\FastPixMetadata;
class GenerateSubtitleTrackRequest
{
    /**
     * A universally unique identifier (UUID) assigned to the media by FastPix.
     *
     * @var string $mediaId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=mediaId')]
    public string $mediaId;

    /**
     * A universally unique identifier (UUID) assigned to the specific track for which subtitles should be generated.
     *
     * @var string $trackId
     */
    #[FastPixMetadata('pathParam:style=simple,explode=false,name=trackId')]
    public string $trackId;

    /**
     *
     * @var Components\TrackSubtitlesGenerateRequest $trackSubtitlesGenerateRequest
     */
    #[FastPixMetadata('request:mediaType=application/json')]
    public Components\TrackSubtitlesGenerateRequest $trackSubtitlesGenerateRequest;

    /**
     * @param  string  $mediaId
     * @param  string  $trackId
     * @param  Components\TrackSubtitlesGenerateRequest  $trackSubtitlesGenerateRequest
     * @phpstan-pure
     */
    public function __construct(string $mediaId, string $trackId, Components\TrackSubtitlesGenerateRequest $trackSubtitlesGenerateRequest)
    {
        $this->mediaId = $mediaId;
        $this->trackId = $trackId;
        $this->trackSubtitlesGenerateRequest = $trackSubtitlesGenerateRequest;
    }
}