<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/**
 * The dimension to group and breakdown the concurrent viewers data by.
 *
 * This determines how the results will be categorized and aggregated.
 * Choose from geographic, content, technical, or behavioral dimensions.
 *
 */
enum GetDataViewlistCurrentViewsFilterDimension: string
{
    case Country = 'country';
    case Region = 'region';
    case AsnId = 'asn_id';
    case Cdn = 'cdn';
    case VideoTitle = 'video_title';
    case VideoSeries = 'video_series';
    case VideoId = 'video_id';
    case SubPropertyId = 'sub_property_id';
    case VideoSourceStreamType = 'video_source_stream_type';
    case OsName = 'os_name';
    case PlayerName = 'player_name';
    case MediaId = 'media_id';
    case FpPlaybackId = 'fp_playback_id';
    case ViewId = 'view_id';
}
