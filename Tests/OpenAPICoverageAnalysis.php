<?php

declare(strict_types=1);

/**
 * OpenAPI Coverage Analysis for FastPix PHP SDK
 * Validates whether all endpoints from sravani.yml are covered in the PHP SDK
 */
class OpenAPICoverageAnalysis
{
    private array $openApiEndpoints = [];
    private array $sdkMethods = [];
    private array $coverage = [];

    public function __construct()
    {
        $this->loadOpenAPIEndpoints();
        $this->loadSDKMethods();
        $this->analyzeCoverage();
    }

    private function loadOpenAPIEndpoints(): void
    {
        // Based on the sravani.yml analysis, here are all the endpoints:
        $this->openApiEndpoints = [
            // On-Demand Media Endpoints
            'POST /on-demand' => 'Create media from URL or direct upload',
            'GET /on-demand' => 'List all media files',
            'GET /on-demand/{mediaId}' => 'Get specific media by ID',
            'PATCH /on-demand/{mediaId}' => 'Update media metadata',
            'DELETE /on-demand/{mediaId}' => 'Delete media',

            // Media Tracks
            'POST /on-demand/{mediaId}/tracks' => 'Add audio/subtitle track',
            'PATCH /on-demand/{mediaId}/tracks/{trackId}' => 'Update track',
            'DELETE /on-demand/{mediaId}/tracks/{trackId}' => 'Delete track',
            'POST /on-demand/{mediaId}/tracks/{trackId}/generate-subtitles' => 'Generate subtitles',

            // Media Features
            'PATCH /on-demand/{mediaId}/summary' => 'Update media summary',
            'PATCH /on-demand/{mediaId}/chapters' => 'Update media chapters',
            'PATCH /on-demand/{mediaId}/named-entities' => 'Update named entities',
            'PATCH /on-demand/{mediaId}/moderation' => 'Update moderation settings',
            'PATCH /on-demand/{mediaId}/source-access' => 'Update source access',
            'PATCH /on-demand/{mediaId}/update-mp4Support' => 'Update MP4 support',
            'GET /on-demand/{mediaId}/input-info' => 'Get input information',

            // Upload Management
            'POST /on-demand/upload' => 'Direct upload',
            'GET /on-demand/uploads' => 'List uploads',
            'PUT /on-demand/upload/{uploadId}/cancel' => 'Cancel upload',

            // Media Clips
            'GET /on-demand/{sourceMediaId}/media-clips' => 'Get media clips',
            'GET /on-demand/{livestreamId}/live-clips' => 'Get live clips',

            // Playback IDs
            'POST /on-demand/{mediaId}/playback-ids' => 'Create playback ID',
            'GET /on-demand/{mediaId}/playback-ids/{playbackId}' => 'Get playback ID',
            'DELETE /on-demand/{mediaId}/playback-ids/{playbackId}' => 'Delete playback ID',

            // Playlists
            'POST /on-demand/playlists' => 'Create playlist',
            'GET /on-demand/playlists' => 'List playlists',
            'GET /on-demand/playlists/{playlistId}' => 'Get playlist by ID',
            'PUT /on-demand/playlists/{playlistId}' => 'Update playlist',
            'DELETE /on-demand/playlists/{playlistId}' => 'Delete playlist',
            'PATCH /on-demand/playlists/{playlistId}/media' => 'Update playlist media',
            'PUT /on-demand/playlists/{playlistId}/media' => 'Replace playlist media',
            'DELETE /on-demand/playlists/{playlistId}/media' => 'Delete playlist media',

            // DRM Configurations
            'GET /on-demand/drm-configurations' => 'List DRM configurations',
            'GET /on-demand/drm-configurations/{drmConfigurationId}' => 'Get DRM configuration',

            // Live Stream Endpoints
            'POST /live/streams' => 'Create live stream',
            'GET /live/streams' => 'List live streams',
            'GET /live/streams/{streamId}' => 'Get live stream by ID',
            'DELETE /live/streams/{streamId}' => 'Delete live stream',
            'PATCH /live/streams/{streamId}' => 'Update live stream',
            'PUT /live/streams/{streamId}/live-enable' => 'Enable live stream',
            'PUT /live/streams/{streamId}/live-disable' => 'Disable live stream',
            'PUT /live/streams/{streamId}/finish' => 'Complete live stream',
            'GET /live/streams/{streamId}/viewer-count' => 'Get viewer count',

            // Live Playback IDs
            'POST /live/streams/{streamId}/playback-ids' => 'Create live playback ID',
            'GET /live/streams/{streamId}/playback-ids/{playbackId}' => 'Get live playback ID',
            'DELETE /live/streams/{streamId}/playback-ids/{playbackId}' => 'Delete live playback ID',

            // Simulcast
            'POST /live/streams/{streamId}/simulcast' => 'Create simulcast target',
            'GET /live/streams/{streamId}/simulcast' => 'List simulcast targets',
            'DELETE /live/streams/{streamId}/simulcast/{simulcastId}' => 'Delete simulcast target',
            'PUT /live/streams/{streamId}/simulcast/{simulcastId}' => 'Update simulcast target',

            // Signing Keys
            'POST /iam/signing-keys' => 'Create signing key',
            'GET /iam/signing-keys' => 'List signing keys',
            'GET /iam/signing-keys/{signingKeyId}' => 'Get signing key by ID',
            'DELETE /iam/signing-keys/{signingKeyId}' => 'Delete signing key',

            // Analytics - Views
            'GET /data/viewlist' => 'Get views data',
            'GET /data/viewlist/{viewId}' => 'Get specific view data',
            'GET /data/viewlist/top-content' => 'Get top content views',
            'GET /data/viewlist/current-views/getTimeseriesViews' => 'Get timeseries views',
            'GET /data/viewlist/current-views/filter' => 'Filter views data',

            // Analytics - Dimensions
            'GET /data/dimensions' => 'List dimensions',
            'GET /data/dimensions/{dimensionsId}' => 'Get dimension data',

            // Analytics - Metrics
            'GET /data/metrics/{metricId}/breakdown' => 'Get metrics breakdown',
            'GET /data/metrics/{metricId}/overall' => 'Get overall metrics',
            'GET /data/metrics/{metricId}/timeseries' => 'Get metrics timeseries',
            'GET /data/metrics/comparison' => 'Get metrics comparison',

            // Analytics - Errors
            'GET /data/errors' => 'Get error data',
        ];
    }

    private function loadSDKMethods(): void
    {
        // Based on the PHP SDK analysis, here are the implemented methods:
        $this->sdkMethods = [
            // InputVideo
            'InputVideo::createMedia' => 'POST /on-demand',
            'InputVideo::directUpload' => 'POST /on-demand/upload',

            // ManageVideos
            'ManageVideos::listMedia' => 'GET /on-demand',
            'ManageVideos::getMedia' => 'GET /on-demand/{mediaId}',
            'ManageVideos::updatedMedia' => 'PATCH /on-demand/{mediaId}',
            'ManageVideos::deleteMedia' => 'DELETE /on-demand/{mediaId}',
            'ManageVideos::addMediaTrack' => 'POST /on-demand/{mediaId}/tracks',
            'ManageVideos::updateMediaTrack' => 'PATCH /on-demand/{mediaId}/tracks/{trackId}',
            'ManageVideos::deleteMediaTrack' => 'DELETE /on-demand/{mediaId}/tracks/{trackId}',
            'ManageVideos::generateSubtitleTrack' => 'POST /on-demand/{mediaId}/tracks/{trackId}/generate-subtitles',
            'ManageVideos::retrieveMediaInputInfo' => 'GET /on-demand/{mediaId}/input-info',
            'ManageVideos::updatedSourceAccess' => 'PATCH /on-demand/{mediaId}/source-access',
            'ManageVideos::updatedMp4Support' => 'PATCH /on-demand/{mediaId}/update-mp4Support',
            'ManageVideos::updateMediaSummary' => 'PATCH /on-demand/{mediaId}/summary',
            'ManageVideos::updateMediaChapters' => 'PATCH /on-demand/{mediaId}/chapters',
            'ManageVideos::updateMediaNamedEntities' => 'PATCH /on-demand/{mediaId}/named-entities',
            'ManageVideos::updateMediaModeration' => 'PATCH /on-demand/{mediaId}/moderation',
            'ManageVideos::listUploads' => 'GET /on-demand/uploads',
            'ManageVideos::cancelUpload' => 'PUT /on-demand/upload/{uploadId}/cancel',
            'ManageVideos::getMediaClips' => 'GET /on-demand/{sourceMediaId}/media-clips',
            'ManageVideos::listLiveClips' => 'GET /on-demand/{livestreamId}/live-clips',

            // Playback
            'Playback::createMediaPlaybackId' => 'POST /on-demand/{mediaId}/playback-ids',
            'Playback::getPlaybackId' => 'GET /on-demand/{mediaId}/playback-ids/{playbackId}',
            'Playback::deleteMediaPlaybackId' => 'DELETE /on-demand/{mediaId}/playback-ids/{playbackId}',

            // Playlist
            'Playlist::createPlaylist' => 'POST /on-demand/playlists',
            'Playlist::listPlaylists' => 'GET /on-demand/playlists',
            'Playlist::getPlaylistById' => 'GET /on-demand/playlists/{playlistId}',
            'Playlist::updatePlaylist' => 'PUT /on-demand/playlists/{playlistId}',
            'Playlist::deletePlaylist' => 'DELETE /on-demand/playlists/{playlistId}',
            'Playlist::updatePlaylistMedia' => 'PATCH /on-demand/playlists/{playlistId}/media',
            'Playlist::replacePlaylistMedia' => 'PUT /on-demand/playlists/{playlistId}/media',
            'Playlist::deletePlaylistMedia' => 'DELETE /on-demand/playlists/{playlistId}/media',

            // DRMConfigurations
            'DRMConfigurations::getDrmConfiguration' => 'GET /on-demand/drm-configurations',
            'DRMConfigurations::getDrmConfigurationById' => 'GET /on-demand/drm-configurations/{drmConfigurationId}',

            // StartLiveStream
            'StartLiveStream::createNewStream' => 'POST /live/streams',
            'StartLiveStream::getStreamById' => 'GET /live/streams/{streamId}',
            'StartLiveStream::updateStream' => 'PATCH /live/streams/{streamId}',

            // ManageLiveStream
            'ManageLiveStream::getAllStreams' => 'GET /live/streams',
            'ManageLiveStream::getLiveStreamById' => 'GET /live/streams/{streamId}',
            'ManageLiveStream::deleteLiveStream' => 'DELETE /live/streams/{streamId}',
            'ManageLiveStream::updateLiveStream' => 'PATCH /live/streams/{streamId}',
            'ManageLiveStream::enableLiveStream' => 'PUT /live/streams/{streamId}/live-enable',
            'ManageLiveStream::disableLiveStream' => 'PUT /live/streams/{streamId}/live-disable',
            'ManageLiveStream::completeLiveStream' => 'PUT /live/streams/{streamId}/finish',
            'ManageLiveStream::getLiveStreamViewerCountById' => 'GET /live/streams/{streamId}/viewer-count',

            // LivePlayback
            'LivePlayback::createLiveStreamPlaybackId' => 'POST /live/streams/{streamId}/playback-ids',
            'LivePlayback::getLiveStreamPlaybackId' => 'GET /live/streams/{streamId}/playback-ids/{playbackId}',
            'LivePlayback::deleteLiveStreamPlaybackId' => 'DELETE /live/streams/{streamId}/playback-ids/{playbackId}',
            'LivePlayback::listLiveStreamPlaybackIds' => 'GET /live/streams/{streamId}/playback-ids',

            // SimulcastStream
            'SimulcastStream::createSimulcastTarget' => 'POST /live/streams/{streamId}/simulcast',
            'SimulcastStream::listSimulcastsOfStream' => 'GET /live/streams/{streamId}/simulcast',
            'SimulcastStream::deleteSimulcastTarget' => 'DELETE /live/streams/{streamId}/simulcast/{simulcastId}',
            'SimulcastStream::updateSimulcastTarget' => 'PUT /live/streams/{streamId}/simulcast/{simulcastId}',

            // SigningKeys
            'SigningKeys::createSigningKey' => 'POST /iam/signing-keys',
            'SigningKeys::getAllSigningKeys' => 'GET /iam/signing-keys',
            'SigningKeys::getSigningKeyById' => 'GET /iam/signing-keys/{signingKeyId}',
            'SigningKeys::deleteSigningKey' => 'DELETE /iam/signing-keys/{signingKeyId}',

            // Views
            'Views::getViewsData' => 'GET /data/viewlist',
            'Views::getViewsByTopContent' => 'GET /data/viewlist/top-content',
            'Views::getViewsCount' => 'GET /data/viewlist/current-views/filter',
            'Views::getViewsByDimension' => 'GET /data/viewlist/{viewId}',
            'Views::getTimeseriesViews' => 'GET /data/viewlist/current-views/getTimeseriesViews',

            // Dimensions
            'Dimensions::listDimensions' => 'GET /data/dimensions',
            'Dimensions::getDimensionsData' => 'GET /data/dimensions/{dimensionsId}',

            // Metrics
            'Metrics::getVideoMetrics' => 'GET /data/metrics/{metricId}/overall',
            'Metrics::getMetricsBreakdown' => 'GET /data/metrics/{metricId}/breakdown',
            'Metrics::getMetricsTimeseries' => 'GET /data/metrics/{metricId}/timeseries',
            'Metrics::getMetricsComparison' => 'GET /data/metrics/comparison',

            // Errors
            'Errors::getErrorData' => 'GET /data/errors',
        ];
    }

    private function analyzeCoverage(): void
    {
        $covered = [];
        $missing = [];

        foreach ($this->openApiEndpoints as $endpoint => $description) {
            $found = false;
            foreach ($this->sdkMethods as $method => $mappedEndpoint) {
                if ($endpoint === $mappedEndpoint) {
                    $covered[$endpoint] = [
                        'description' => $description,
                        'sdk_method' => $method,
                    ];
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                $missing[$endpoint] = $description;
            }
        }

        $this->coverage = [
            'covered' => $covered,
            'missing' => $missing,
            'total_endpoints' => count($this->openApiEndpoints),
            'covered_count' => count($covered),
            'missing_count' => count($missing),
            'coverage_percentage' => round((count($covered) / count($this->openApiEndpoints)) * 100, 2),
        ];
    }

    public function displayAnalysis(): void
    {
        echo "\n".str_repeat('=', 100)."\n";
        echo "FASTPIX PHP SDK - OPENAPI COVERAGE ANALYSIS\n";
        echo str_repeat('=', 100)."\n";
        echo "Analysis of endpoint coverage based on sravani.yml\n";
        echo 'Timestamp: '.date('Y-m-d H:i:s')."\n";
        echo str_repeat('=', 100)."\n\n";

        echo "COVERAGE SUMMARY:\n";
        echo str_repeat('-', 50)."\n";
        echo "Total OpenAPI Endpoints: {$this->coverage['total_endpoints']}\n";
        echo "✅ Covered by PHP SDK: {$this->coverage['covered_count']}\n";
        echo "❌ Missing from PHP SDK: {$this->coverage['missing_count']}\n";
        echo "📊 Coverage Percentage: {$this->coverage['coverage_percentage']}%\n\n";

        if (! empty($this->coverage['covered'])) {
            echo "✅ COVERED ENDPOINTS:\n";
            echo str_repeat('-', 50)."\n";
            foreach ($this->coverage['covered'] as $endpoint => $info) {
                echo "• {$endpoint}\n";
                echo "  Description: {$info['description']}\n";
                echo "  SDK Method: {$info['sdk_method']}\n\n";
            }
        }

        if (! empty($this->coverage['missing'])) {
            echo "❌ MISSING ENDPOINTS:\n";
            echo str_repeat('-', 50)."\n";
            foreach ($this->coverage['missing'] as $endpoint => $description) {
                echo "• {$endpoint}\n";
                echo "  Description: {$description}\n\n";
            }
        }

        echo str_repeat('=', 100)."\n";
        echo "DETAILED BREAKDOWN BY CATEGORY:\n";
        echo str_repeat('-', 50)."\n";

        // Category coverage counts (fixes bug when passing arrays to strpos)
        $coveredEndpoints = array_keys($this->coverage['covered']);
        $countByPrefix = function (string $prefix) use ($coveredEndpoints): int {
            $count = 0;
            foreach ($coveredEndpoints as $ep) {
                if (strpos($ep, $prefix) === 0) {
                    $count++;
                }
            }

            return $count;
        };

        echo "\n📁 On-Demand Media: ".$countByPrefix('/on-demand')." endpoints covered\n";
        echo '📁 Live Streaming: '.$countByPrefix('/live')." endpoints covered\n";
        echo '📁 Signing Keys: '.$countByPrefix('/iam')." endpoints covered\n";
        echo '📁 Analytics: '.$countByPrefix('/data')." endpoints covered\n";

        echo "\n".str_repeat('=', 100)."\n";
        echo "CONCLUSION:\n";
        echo str_repeat('-', 20)."\n";

        if ($this->coverage['coverage_percentage'] >= 95) {
            echo "🎉 EXCELLENT: PHP SDK has comprehensive coverage of the OpenAPI specification!\n";
        } elseif ($this->coverage['coverage_percentage'] >= 90) {
            echo "✅ GOOD: PHP SDK covers most endpoints with minor gaps.\n";
        } elseif ($this->coverage['coverage_percentage'] >= 80) {
            echo "⚠️  FAIR: PHP SDK covers majority of endpoints but some are missing.\n";
        } else {
            echo "❌ POOR: PHP SDK is missing significant endpoint coverage.\n";
        }

        echo "\nThe FastPix PHP SDK provides ".$this->coverage['coverage_percentage']."% coverage of the OpenAPI specification.\n";
        echo str_repeat('=', 100)."\n";
    }

    public function getCoverage(): array
    {
        return $this->coverage;
    }
}

// Run the analysis
$analysis = new OpenAPICoverageAnalysis();
$analysis->displayAnalysis();
