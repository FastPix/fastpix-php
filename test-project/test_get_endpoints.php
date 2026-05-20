<?php

/**
 * FastPix PHP SDK — GET endpoint smoke test (SDK-only)
 * ------------------------------------------------------------------
 * Every endpoint below is exercised ONLY through the SDK methods.
 * There are no direct API/curl calls — the SDK itself performs the
 * HTTP requests. Read-only: nothing is created or modified.
 *
 * USAGE
 *   php test_get_endpoints.php
 *
 * STATUS
 *   PASS   SDK returned HTTP 2xx
 *   HTTP   SDK reached the API but it returned 4xx/5xx (reason shown)
 *   DESER  HTTP 200 but the SDK failed to deserialize the body (SDK bug)
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components as C;
use FastPix\Sdk\Models\Operations as O;

// SDK serializer emits notices on some union types; keep output readable.
error_reporting(E_ERROR | E_PARSE);

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(
        new C\Security(
            username: 'bb18eef3-19ff-46cb-931a-469ac4ae3a52',
            password: '36976976-5842-48c6-8a69-b06cedbb8448',
        )
    )
    ->build();

// Real IDs from the workspace. (DRM/simulcast/view have no data → those calls
// will report a normal API error, which is expected.)
$mediaId          = 'f7510a27-dfc0-4e64-828a-f7b2cc69834a';
$mediaPlaybackId  = '5430a2fc-c2af-4c83-bb11-aa3be8e6a8c9';
$playlistId       = '37db26df-f813-4edf-8a39-839a3778a94a';
$signingKeyId     = 'f2513044-e146-4e31-aa2d-9100c466a754';
$streamId         = 'f45333b60ffdc5e95bc8eefc82565c43';
$streamPlaybackId = '948aac5c-f36f-4063-b894-71be3053f885';
$drmConfigId      = 'your-drm-configuration-id';  // none in workspace
$simulcastId      = 'your-simulcast-id';          // none in workspace
$viewId           = 'your-view-id';               // none in workspace

$rows = [];

/** Invoke one SDK operation, classify, and record the result. */
function check(array &$rows, string $endpoint, callable $sdkCall): void
{
    try {
        $resp = $sdkCall();
        $code = $resp->statusCode ?? 0;
        $rows[] = [$code >= 200 && $code < 300 ? 'PASS' : 'HTTP', $code, $endpoint, ''];
    } catch (\FastPix\Sdk\Models\Errors\APIException $e) {
        $reason = '';
        $body = json_decode((string) ($e->body ?? ''), true);
        if (isset($body['error']['message'])) {
            $reason = $body['error']['message'];
            if (!empty($body['error']['fields'][0]['field'])) {
                $reason .= ' (' . $body['error']['fields'][0]['field'] . ')';
            }
        }
        $rows[] = ['HTTP', $e->getCode(), $endpoint, $reason];
    } catch (\Throwable $t) {
        $rows[] = ['DESER', '200*', $endpoint, 'SDK deserialize: ' . substr($t->getMessage(), 0, 50)];
    }
}

// -------------------------------------------------------------- Media (VOD)
check($rows, 'GET /on-demand',                         fn () => $sdk->manageVideos->listMedia(limit: 5, offset: 1, orderBy: C\SortOrder::Desc));
check($rows, 'GET /on-demand/uploads',                 fn () => $sdk->manageVideos->listUploads(limit: 5, offset: 1, orderBy: C\SortOrder::Desc));
check($rows, 'GET /on-demand/{mediaId}',               fn () => $sdk->manageVideos->getMedia($mediaId));
check($rows, 'GET /on-demand/{mediaId}/summary',       fn () => $sdk->manageVideos->getMediaSummary($mediaId));
check($rows, 'GET /on-demand/{mediaId}/input-info',    fn () => $sdk->manageVideos->retrieveMediaInputInfo($mediaId));
check($rows, 'GET /on-demand/{mediaId}/media-clips',   fn () => $sdk->manageVideos->getMediaClips($mediaId, offset: 1, limit: 5, orderBy: C\SortOrder::Desc));
check($rows, 'GET /on-demand/{livestreamId}/live-clips', fn () => $sdk->manageVideos->listLiveClips($streamId, limit: 5, offset: 1, orderBy: C\SortOrder::Desc));

// ------------------------------------------------------------- Playback IDs
check($rows, 'GET /on-demand/{mediaId}/playback-ids',  fn () => $sdk->playback->listPlaybackIds($mediaId));
check($rows, 'GET /on-demand/{mediaId}/playback-ids/{pid}', fn () => $sdk->playback->getPlaybackId($mediaId, $mediaPlaybackId));

// ----------------------------------------------------------------- Playlists
check($rows, 'GET /on-demand/playlists',               fn () => $sdk->playlist->getAllPlaylists(limit: 5, offset: 1));
check($rows, 'GET /on-demand/playlists/{playlistId}',  fn () => $sdk->playlist->getPlaylistById($playlistId));

// ----------------------------------------------------------------------- DRM
check($rows, 'GET /on-demand/drm-configurations',      fn () => $sdk->drmConfigurations->getDrmConfiguration(offset: 1, limit: 5));
check($rows, 'GET /on-demand/drm-configurations/{id}', fn () => $sdk->drmConfigurations->getDrmConfigurationById($drmConfigId));

// -------------------------------------------------------------- Live streams
check($rows, 'GET /live/streams',                      fn () => $sdk->manageLiveStream->getAllStreams(limit: 5, offset: 1, orderBy: O\OrderBy::Desc));
check($rows, 'GET /live/streams/{streamId}',           fn () => $sdk->manageLiveStream->getLiveStreamById($streamId));
check($rows, 'GET /live/streams/{streamId}/viewer-count', fn () => $sdk->manageLiveStream->getLiveStreamViewerCountById($streamId));
check($rows, 'GET /live/streams/{streamId}/playback-ids/{pid}', fn () => $sdk->livePlayback->getLiveStreamPlaybackId($streamId, $streamPlaybackId));
check($rows, 'GET /live/streams/{streamId}/simulcast/{sid}', fn () => $sdk->simulcastStream->getSpecificSimulcastOfStream($streamId, $simulcastId));

// --------------------------------------------------------------- Signing keys
check($rows, 'GET /iam/signing-keys',                  fn () => $sdk->signingKeys->listSigningKeys(limit: 5, offset: 1));
check($rows, 'GET /iam/signing-keys/{signingKeyId}',   fn () => $sdk->signingKeys->getSigningKeyById($signingKeyId));

// ------------------------------------------------------------ Analytics/Data
check($rows, 'GET /data/dimensions',                   fn () => $sdk->dimensions->listDimensions());
check($rows, 'GET /data/dimensions/{dimensionsId}',    fn () => $sdk->dimensions->listFilterValuesForDimension(dimensionsId: O\DimensionsId::BrowserName, timespan: O\ListFilterValuesForDimensionTimespan::Sevendays));
check($rows, 'GET /data/errors',                       fn () => $sdk->errors->listErrors(timespan: O\ListErrorsTimespan::Sevendays, limit: 5));
check($rows, 'GET /data/metrics/comparison',           fn () => $sdk->metrics->listComparisonValues(timespan: O\ListComparisonValuesTimespan::Sevendays, dimension: O\Dimension::BrowserName, value: 'Chrome'));
check($rows, 'GET /data/metrics/{metricId}/overall',   fn () => $sdk->metrics->listOverallValues(metricId: O\ListOverallValuesMetricId::Views, timespan: O\ListOverallValuesTimespan::Sevendays));
check($rows, 'GET /data/metrics/{metricId}/timeseries', fn () => $sdk->metrics->getTimeseriesData(new O\GetTimeseriesDataRequest(metricId: O\GetTimeseriesDataMetricId::Views, timespan: O\GetTimeseriesDataTimespan::Sevendays, measurement: 'count')));
check($rows, 'GET /data/metrics/{metricId}/breakdown', fn () => $sdk->metrics->listBreakdownValues(new O\ListBreakdownValuesRequest(metricId: O\ListBreakdownValuesMetricId::Views, timespan: O\ListBreakdownValuesTimespan::Sevendays, measurement: 'count')));
check($rows, 'GET /data/viewlist',                     fn () => $sdk->views->listVideoViews(new O\ListVideoViewsRequest(timespan: O\ListVideoViewsTimespan::Sevendays, limit: 5, offset: 1)));
check($rows, 'GET /data/viewlist/top-content',         fn () => $sdk->views->listByTopContent(timespan: O\ListByTopContentTimespan::Sevendays, limit: 5));
check($rows, 'GET /data/viewlist/{viewId}',            fn () => $sdk->views->getVideoViewDetails($viewId));

// --------------------------------------------------------------------- report
$tally = ['PASS' => 0, 'HTTP' => 0, 'DESER' => 0];
echo str_pad('STATUS', 8) . str_pad('CODE', 7) . str_pad('ENDPOINT', 52) . "NOTE\n";
echo str_repeat('-', 110) . "\n";
foreach ($rows as [$status, $code, $endpoint, $note]) {
    echo str_pad($status, 8) . str_pad((string) $code, 7) . str_pad($endpoint, 52) . $note . "\n";
    $tally[$status] = ($tally[$status] ?? 0) + 1;
}
echo str_repeat('-', 110) . "\n";
printf(
    "Total=%d   PASS=%d   API-rejected(HTTP)=%d   deserialize-bug(DESER)=%d\n",
    count($rows), $tally['PASS'], $tally['HTTP'], $tally['DESER']
);
