<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/**
 * Pass metric Id
 *
 *
 */
enum GetTimeseriesDataMetricId: string
{
    case Views = 'views';
    case UniqueViewers = 'unique_viewers';
    case PlayingTime = 'playing_time';
    case QualityOfExperienceScore = 'quality_of_experience_score';
    case PlaybackScore = 'playback_score';
    case PlaybackFailurePercentage = 'playback_failure_percentage';
    case ExitBeforeVideoStart = 'exit_before_video_start';
    case VideoStartupFailurePercentage = 'video_startup_failure_percentage';
    case StartupScore = 'startup_score';
    case VideoStartupTime = 'video_startup_time';
    case PlayerStartupTime = 'player_startup_time';
    case PageLoadTime = 'page_load_time';
    case TotalStartupTime = 'total_startup_time';
    case LiveStreamLatency = 'live_stream_latency';
    case AverageBitrate = 'average_bitrate';
    case BufferCount = 'buffer_count';
    case RenderQualityScore = 'render_quality_score';
    case AvgUpscaling = 'avg_upscaling';
    case AvgDownscaling = 'avg_downscaling';
    case MaxUpscaling = 'max_upscaling';
    case MaxDownscaling = 'max_downscaling';
    case JumpLatency = 'jump_latency';
    case StabilityScore = 'stability_score';
    case BufferRatio = 'buffer_ratio';
    case BufferFrequency = 'buffer_frequency';
    case BufferFill = 'buffer_fill';
}
