<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/** The maximum resolution for the playback ID. */
enum Resolution: string
{
    case FourHundredAndEightyp = '480p';
    case SevenHundredAndTwentyp = '720p';
    case OneThousandAndEightyp = '1080p';
    case OneThousandFourHundredAndFortyp = '1440p';
    case TwoThousandOneHundredAndSixtyp = '2160p';
}
