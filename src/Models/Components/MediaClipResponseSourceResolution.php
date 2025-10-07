<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** The actual resolution of the uploaded media. */
enum MediaClipResponseSourceResolution: string
{
    case TwoThousandOneHundredAndSixtyp = '2160p';
    case OneThousandFourHundredAndFortyp = '1440p';
    case OneThousandAndEightyp = '1080p';
    case SevenHundredAndTwentyp = '720p';
    case FourHundredAndEightyp = '480p';
    case ThreeHundredAndSixtyp = '360p';
}
