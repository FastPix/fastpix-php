<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Views - Displays the result of the request. */
class Views
{
    /**
     * It is a unique identifier associated with a specific workspace within the FastPix platform.
     *
     *
     *
     * @var ?string $workspaceId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('workspaceId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $workspaceId = null;

    /**
     * Events specifies the order of events journey of the video playback 
     *
     *
     *
     * @var ?array<Event> $events
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('events')]
    #[\Speakeasy\Serializer\Annotation\Type('array<\FastPix\Sdk\Models\Components\Event>|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?array $events = null;

    /**
     * Exit Before Video Start indicates whether a viewer abandoned the video before it started playing, typically due to long loading times.
     *
     *
     *
     * @var ?bool $exitBeforeVideoStart
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('exitBeforeVideoStart')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $exitBeforeVideoStart = null;

    /**
     * Experiment Name is used in A/B testing scenarios to categorize video views into different experiments.
     *
     *
     *
     * @var ?string $experimentName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('experimentName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $experimentName = null;

    /**
     * Insert Timestamp refers to the time instance when the view is started.
     *
     *
     *
     * @var ?string $insertTimestamp
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('insertTimestamp')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $insertTimestamp = null;

    /**
     * Player Autoplay On indicates whether the video player automatically initiated playback of the video content.
     *
     *
     *
     * @var ?bool $playerAutoplayOn
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerAutoplayOn')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $playerAutoplayOn = null;

    /**
     * Player Preload On indicates whether the player is configured to preload the video content upon page load.
     *
     *
     *
     * @var ?bool $playerPreloadOn
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerPreloadOn')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $playerPreloadOn = null;

    /**
     * Player Remote Played specifies if the video is being remotely played to devices such as AirPlay or Chromecast, obtained from the SDK.
     *
     *
     *
     * @var ?bool $playerRemotePlayed
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerRemotePlayed')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $playerRemotePlayed = null;

    /**
     * Used Fullscreen denotes whether the viewer utilized the full-screen mode while watching the video.
     *
     *
     *
     * @var ?bool $usedFullScreen
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('usedFullScreen')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $usedFullScreen = null;

    /**
     * Video Startup Failure is a boolean metric indicating whether a viewer encountered an error before the first frame of the video commenced playback.
     *
     *
     *
     * @var ?bool $videoStartupFailed
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoStartupFailed')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $videoStartupFailed = null;

    /**
     * View Has Ad is a boolean metric indicating whether an advertisement played or attempted to play during the video view.
     *
     *
     *
     * @var ?bool $viewHasAd
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewHasAd')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?bool $viewHasAd = null;

    /**
     * View ID is a unique identifier assigned to each individual video viewing session.
     *
     *
     *
     * @var ?string $viewId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $viewId = null;

    /**
     * Operating System Version specifies the specific version of the operating system being used by the viewer
     *
     *
     *
     * @var ?string $osVersion
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('osVersion')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $osVersion = null;

    /**
     * The Name associated with the asnId.
     *
     *
     *
     * @var ?string $asnName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('asnName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $asnName = null;

    /**
     * The unique identifier assigned to an Autonomous System (AS) on the Internet. The ASN is used to identify and exchange routing information between different networks.
     *
     *
     *
     * @var ?int $asnId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('asnId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $asnId = null;

    /**
     * The media Id value if the video asset is internal to FastPix.
     *
     *
     *
     * @var ?string $mediaId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('mediaId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $mediaId = null;

    /**
     * Buffer Count represents the number of rebuffering events occurring during the video view.
     *
     *
     *
     * @var ?int $bufferCount
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('bufferCount')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $bufferCount = null;

    /**
     * Buffer Fill indicates the total time, in milliseconds, that viewers wait for rebuffering per video view.         
     *
     *
     *
     * @var ?int $bufferFill
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('bufferFill')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $bufferFill = null;

    /**
     * Buffer Frequency measures the rate at which rebuffering events occur, expressed as events per millisecond.
     *
     *
     *
     * @var ?float $bufferFrequency
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('BufferFrequency')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $bufferFrequency = null;

    /**
     * Content Delivery Network (CDN) refers to the network infrastructure responsible for delivering the video content to the viewer.        
     *
     *
     *
     * @var ?string $cdn
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('cdn')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $cdn = null;

    /**
     * City indicates the geographical location of the viewer accessing the video content.        
     *
     *
     *
     * @var ?string $city
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('city')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $city = null;

    /**
     * Continent represents the continent name of the viewer accessing the video content.    
     *
     *
     *
     * @var ?string $continent
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('continent')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $continent = null;

    /**
     * Country Code denotes the two-letter ISO code representing the country of origin for the viewer accessing the video content.      
     *
     *
     *
     * @var ?string $countryCode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('countryCode')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $countryCode = null;

    /**
     * Country represents the coded text that represents the country name of viewer accessing the video content.      
     *
     *
     *
     * @var ?string $country
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('country')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $country = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom1
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom1')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom1 = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom2
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom2')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom2 = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom3
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom3')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom3 = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom4
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom4')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom4 = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom5
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom5')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom5 = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom6
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom6')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom6 = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom7
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom7')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom7 = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom8
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom8')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom8 = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom9
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom9')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom9 = null;

    /**
     * User defined metadata. Only accessible once it is enabled in the organization settings.
     *
     *
     *
     * @var ?string $custom10
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('custom10')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $custom10 = null;

    /**
     * Latitude refers to the geographical coordinate representing the north-south position of the viewer's location, truncated to one decimal place.
     *
     *
     *
     * @var ?string $latitude
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('latitude')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $latitude = null;

    /**
     * FastPix Live Stream ID is the unique identifier associated with a live stream video media within the FastPix Video platform.
     *
     *
     *
     * @var ?string $fpLiveStreamId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('fpLiveStreamId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $fpLiveStreamId = null;

    /**
     * Live Stream Latency measures the average time taken from the point of ingest to the point of display for live stream video views.
     *
     *
     *
     * @var ?float $liveStreamLatency
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('liveStreamLatency')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $liveStreamLatency = null;

    /**
     * Longitude denotes the geographical coordinate representing the east-west position of the viewer's location, truncated to one decimal place.
     *
     *
     *
     * @var ?string $longitude
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('longitude')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $longitude = null;

    /**
     * Page Load Time measures the time from when the user initiates loading the page to when all resources are loaded on the page.
     *
     *
     *
     * @var ?int $pageLoadTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pageLoadTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $pageLoadTime = null;

    /**
     * Page Context provides contextual information about the type of page being accessed.
     *
     *
     *
     * @var ?string $pageContext
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('pageContext')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $pageContext = null;

    /**
     * View Page URL denotes the URL address of the web page where the video content is being accessed.
     *
     *
     *
     * @var ?string $viewPageUrl
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewPageUrl')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $viewPageUrl = null;

    /**
     * FastPix Playback ID refers to the unique identifier associated with the playback instance of a video, particularly used in FastPix Video platform.
     *
     *
     *
     * @var ?string $fpPlaybackId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('fpPlaybackId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $fpPlaybackId = null;

    /**
     * Playback Success Score represents a numerical value indicating the success or quality of the video playback experience.
     *
     *
     *
     * @var ?float $playbackScore
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playbackScore')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $playbackScore = null;

    /**
     * Error Code is an identifier representing a specific type of error that occurred during video playback, potentially leading to playback failure.
     *
     *
     *
     * @var ?string $errorCode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('errorCode')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $errorCode = null;

    /**
     * Error Message is a descriptive message generated by the video player when an error occurs during playback, associated with an error code.
     *
     *
     *
     * @var ?string $errorMessage
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('errorMessage')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $errorMessage = null;

    /**
     * Player Height refers to the vertical dimension, measured in pixels, of the video player as it appears on the webpage.
     *
     *
     *
     * @var string|float|null $playerHeight
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerHeight')]
    #[\Speakeasy\Serializer\Annotation\Type('string|float|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public string|float|null $playerHeight = null;

    /**
     * Player Instance ID is a unique identifier that distinguishes each instance of the Player class created when initializing a video.
     *
     *
     *
     * @var ?string $playerInstanceId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerInstanceId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $playerInstanceId = null;

    /**
     * Player Language indicates the language used for text elements within the video player interface.
     *
     *
     *
     * @var ?string $playerLanguage
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerLanguage')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $playerLanguage = null;

    /**
     * FastPix SDK Name identifies the name of the FastPix Player SDK utilized within the player workspace.
     *
     *
     *
     * @var ?string $fpSDK
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('fpSdk')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $fpSDK = null;

    /**
     * FastPix SDK Version specifies the version of the FastPix Player SDK integrated into the player.
     *
     *
     *
     * @var ?string $fpSDKVersion
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('fpSdkVersion')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $fpSDKVersion = null;

    /**
     * Player Name serves to differentiate various configurations or types of players used across the website or application.
     *
     *
     *
     * @var ?string $playerName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $playerName = null;

    /**
     * Player Poster refers to the image displayed as a preview before the video playback begins.
     *
     *
     *
     * @var ?string $playerPoster
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerPoster')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $playerPoster = null;

    /**
     * Player Software Version indicates the version number of the player software installed.
     *
     *
     *
     * @var ?string $playerSoftwareVersion
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerSoftwareVersion')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $playerSoftwareVersion = null;

    /**
     * Player Software Name denotes the software utilized for video playback within the player workspace.
     *
     *
     *
     * @var ?string $playerSoftwareName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerSoftwareName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $playerSoftwareName = null;

    /**
     * Video Source Domain identifies the domain from which the video source originates.
     *
     *
     *
     * @var ?string $videoSourceDomain
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoSourceDomain')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoSourceDomain = null;

    /**
     * Video Source Duration represents the duration of the video source content, measured in milliseconds.
     *
     *
     *
     * @var ?int $videoSourceDuration
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoSourceDuration')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $videoSourceDuration = null;

    /**
     * Player Source Height denotes the vertical dimension, measured in pixels, of the source video content being transmitted to the player.
     *
     *
     *
     * @var ?int $playerSourceHeight
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerSourceHeight')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $playerSourceHeight = null;

    /**
     * Video Source Hostname represents the hostname of the video
     *
     *
     *
     * @var ?string $videoSourceHostname
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoSourceHostname')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoSourceHostname = null;

    /**
     * Video Source Stream Type denotes the type of stream used by the player, although it is currently unused.
     *
     *
     *
     * @var ?string $videoSourceStreamType
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoSourceStreamType')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoSourceStreamType = null;

    /**
     * Video Source Type denotes the format of the video source as determined by the player, including formats
     *
     *
     *
     * @var ?string $videoSourceType
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoSourceType')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoSourceType = null;

    /**
     * Player Source URL refers to the URL of the video source accessed by the player.
     *
     *
     *
     * @var ?string $videoSourceUrl
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoSourceUrl')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoSourceUrl = null;

    /**
     * Source Width represents the width of the source video as perceived by the player, typically measured in pixels.
     *
     *
     *
     * @var ?int $playerSourceWidth
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerSourceWidth')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $playerSourceWidth = null;

    /**
     * Player Initialisation Time measures the duration, in milliseconds, from the initialization of the player within the webpage to its readiness to receive further instructions.
     *
     *
     *
     * @var ?int $playerInitializationTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerInitializationTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $playerInitializationTime = null;

    /**
     * Player Version indicates the version of the player used to render the video content. It is often utilized for performance comparison between different player versions.
     *
     *
     *
     * @var ?string $playerVersion
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerVersion')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $playerVersion = null;

    /**
     * Player Width refers to the width of the player displayed within the webpage, measured in pixels.
     *
     *
     *
     * @var string|float|null $playerWidth
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerWidth')]
    #[\Speakeasy\Serializer\Annotation\Type('string|float|null')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public string|float|null $playerWidth = null;

    /**
     * Render Quality Score is a decimal value representing the score indicating the perceived quality of the video.
     *
     *
     *
     * @var ?float $renderQualityScore
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('renderQualityScore')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $renderQualityScore = null;

    /**
     * Buffer Ratio refers to the percentage of time during video playback where the viewer experiences buffering or rebuffering events.  
     *
     *
     *
     * @var ?float $bufferRatio
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('bufferRatio')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $bufferRatio = null;

    /**
     * Stability Score quantifies the smoothness of video playback, typically represented as a decimal value.
     *
     *
     *
     * @var ?float $stabilityScore
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('stabilityScore')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $stabilityScore = null;

    /**
     * Region denotes the geographical region of the viewer accessing the video content.
     *
     *
     *
     * @var ?string $region
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('region')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $region = null;

    /**
     * Session ID refers to the unique identifier tracking a viewer's session within the FastPix platform.
     *
     *
     *
     * @var ?string $sessionId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('sessionId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $sessionId = null;

    /**
     * Startup Time Score evaluates the startup performance of the player, usually represented as a decimal value      
     *
     *
     *
     * @var ?float $startupScore
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('startupScore')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $startupScore = null;

    /**
     * Sub Property ID denotes the unique identifier assigned to FastPix properties, previously linked with a specific workspace.
     *
     *
     *
     * @var ?string $subPropertyId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('subPropertyId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $subPropertyId = null;

    /**
     * Video Startup Time measures the duration, in milliseconds, from the initialization of the player within the webpage to its readiness to receive further instructions.
     *
     *
     *
     * @var ?int $videoStartupTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoStartupTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $videoStartupTime = null;

    /**
     * Updated Timestamp refers to when the record is updated to a particular Video.
     *
     *
     *
     * @var ?string $updatedTimestamp
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('updatedTimestamp')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $updatedTimestamp = null;

    /**
     * Video Content Type specifies the classification of the video content.
     *
     *
     *
     * @var ?string $videoContentType
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoContentType')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoContentType = null;

    /**
     * Video Duration represents the length of the video, provided in milliseconds, typically supplied to FastPix via custom metadata.
     *
     *
     *
     * @var ?int $videoDuration
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoDuration')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $videoDuration = null;

    /**
     * Video ID refers to an internal identifier assigned by the user or system to uniquely identify a particular video.
     *
     *
     *
     * @var ?string $videoId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoId = null;

    /**
     * Video Language denotes the primary audio language of the video content, assuming it remains unchanged after playback initiation.
     *
     *
     *
     * @var ?string $videoLanguage
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoLanguage')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoLanguage = null;

    /**
     * Video Series denotes the name of a series to which the video content belongs.
     *
     *
     *
     * @var ?string $videoSeries
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoSeries')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoSeries = null;

    /**
     * Video Title refers to the title of the video content being viewed.
     *
     *
     *
     * @var ?string $videoTitle
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoTitle')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoTitle = null;

    /**
     * Average Request Latency average time it takes for a request to be made and processed during video playback
     *
     *
     *
     * @var ?float $avgRequestLatency
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('avgRequestLatency')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $avgRequestLatency = null;

    /**
     * Average Request Throughput refers to the average throughput or data transfer rate of HTTP requests made during video playback
     *
     *
     *
     * @var ?float $avgRequestThroughput
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('avgRequestThroughput')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $avgRequestThroughput = null;

    /**
     * DRM Type indicates the type of Digital Rights Management (DRM) utilized during video playback
     *
     *
     *
     * @var ?string $drmType
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('drmType')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $drmType = null;

    /**
     * Dropped Frame Count represents the number of frames dropped by the video player during playback.
     *
     *
     *
     * @var ?int $droppedFrameCount
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('droppedFrameCount')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $droppedFrameCount = null;

    /**
     * View End refers to the date and time, in Coordinated Universal Time (UTC), when the video viewing session concluded.
     *
     *
     *
     * @var ?string $viewEnd
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewEnd')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $viewEnd = null;

    /**
     * Maximum Downscale Percentage represents the highest percentage of downscaling applied to the video during the view.
     *
     *
     *
     * @var ?float $maxDownscaling
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxDownscaling')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $maxDownscaling = null;

    /**
     * View Max Playhead Position represents the furthest point reached by the playhead during the video view, measured in milliseconds.
     *
     *
     *
     * @var ?int $viewMaxPlayheadPosition
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewMaxPlayheadPosition')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $viewMaxPlayheadPosition = null;

    /**
     * Max request Latency refers to the maximum rate of data transfer (throughput) during requests made by the playback.
     *
     *
     *
     * @var ?float $maxRequestLatency
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxRequestLatency')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $maxRequestLatency = null;

    /**
     * Maximum Upscale Percentage represents the highest percentage of upscaling applied to the video during the view.
     *
     *
     *
     * @var ?float $maxUpscaling
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('maxUpscaling')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $maxUpscaling = null;

    /**
     * Playing Time denotes the total duration of time the video content was actively playing during the view, excluding time spent buffering, seeking, or joining.
     *
     *
     *
     * @var ?int $viewPlayingTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewPlayingTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $viewPlayingTime = null;

    /**
     * View Seeked Count signifies the number of times the viewer attempted to seek to a new location within the video.
     *
     *
     *
     * @var ?int $viewSeekedCount
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewSeekedCount')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $viewSeekedCount = null;

    /**
     * View Seeked Duration indicates the total duration of time spent waiting for playback to resume after the viewer seeks to a new location. Seek Latency metric in the Dashboard is derived by dividing this value by the view_seek_count.
     *
     *
     *
     * @var ?int $viewSeekedDuration
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewSeekedDuration')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $viewSeekedDuration = null;

    /**
     * View Start refers to the date and time, in Coordinated Universal Time (UTC), when the video viewing session commenced.
     *
     *
     *
     * @var ?string $viewStart
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewStart')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $viewStart = null;

    /**
     * View Total content Playback Time represents the cumulative duration of video content watched by the viewer, measured in milliseconds. This metric is internally utilized to calculate upscale and downscale percentages.
     *
     *
     *
     * @var ?int $viewTotalContentPlaybackTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewTotalContentPlaybackTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $viewTotalContentPlaybackTime = null;

    /**
     * Average Downscaling refers to the average reduction in video resolution or quality during the playback of video content.
     *
     *
     *
     * @var ?float $avgDownscaling
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('avgDownscaling')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $avgDownscaling = null;

    /**
     * Average Upscaling refers to the average resolution of the video source is lower than the resolution of the playback device or screen.
     *
     *
     *
     * @var ?float $avgUpscaling
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('avgUpscaling')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $avgUpscaling = null;

    /**
     * Browser denotes the software application utilized by the viewer to access and watch the video content
     *
     *
     *
     * @var ?string $browserName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('browserName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $browserName = null;

    /**
     * Browser version signifies the specific version of the browser software employed by the viewer
     *
     *
     *
     * @var ?string $browserVersion
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('browserVersion')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $browserVersion = null;

    /**
     * Connection Type signifies the type of network connection utilized by the viewer's device
     *
     *
     *
     * @var ?string $connectiontype
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('connectiontype')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $connectiontype = null;

    /**
     * Device Type denotes the classification of the device used by the viewer
     *
     *
     *
     * @var ?string $deviceType
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('deviceType')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $deviceType = null;

    /**
     * Device Manufacturer indicates the brand or manufacturer of the device used by the viewer.
     *
     *
     *
     * @var ?string $deviceManufacturer
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('deviceManufacturer')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $deviceManufacturer = null;

    /**
     * Device Model represents the specific model of the device used by the viewer.
     *
     *
     *
     * @var ?string $deviceModel
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('deviceModel')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $deviceModel = null;

    /**
     * Device Name refers to the name or label assigned to the device used by the viewer.
     *
     *
     *
     * @var ?string $deviceName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('deviceName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $deviceName = null;

    /**
     * Quality Of Experience Score quantifies the overall viewer experience based on various metrics, providing a decimal score to assess the quality of the viewing experience.        
     *
     *
     *
     * @var ?float $qualityOfExperienceScore
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('qualityOfExperienceScore')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $qualityOfExperienceScore = null;

    /**
     * Operating System signifies the name of software platform utilized by the viewer.
     *
     *
     *
     * @var ?string $osName
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('osName')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $osName = null;

    /**
     * User Agent represents the user agent string transmitted by the viewer's device to identify itself to the server, typically including information about the device and browser.
     *
     *
     *
     * @var ?string $userAgent
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('userAgent')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $userAgent = null;

    /**
     * Viewer ID refers to a customer-defined identifier representing the viewer who is watching the video stream. It should be anonymized and not contain any personally identifiable information.
     *
     *
     *
     * @var ?string $viewerId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewerId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $viewerId = null;

    /**
     * Total Watch Time denotes the total duration of video content watched by the viewer, encompassing startup time, playing time, and potential rebuffering time, measured in milliseconds.
     *
     *
     *
     * @var ?int $totalWatchTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('totalWatchTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $totalWatchTime = null;

    /**
     * Average Bitrate represents the average bitrate of the video content watched by the viewer, expressed in bits per second (bps). This metric provides insight into the quality of the video stream.
     *
     *
     *
     * @var ?float $averageBitrate
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('averageBitrate')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $averageBitrate = null;

    /**
     * Jump Latency refers to the delay or latency experienced when there is a jump or seek action performed by the viewer while watching a video. 
     *
     *
     *
     * @var ?float $jumpLatency
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('jumpLatency')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $jumpLatency = null;

    /**
     * Player Resolution refers to the resolution of the video player window or viewport where the video content is being displayed.
     *
     *
     *
     * @var ?string $playerResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('playerResolution')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $playerResolution = null;

    /**
     * videoResolution refers to the resolution of the video being played.
     *
     *
     *
     * @var ?string $videoResolution
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoResolution')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $videoResolution = null;

    /**
     * @param  ?string  $workspaceId
     * @param  ?array<Event>  $events
     * @param  ?bool  $exitBeforeVideoStart
     * @param  ?string  $experimentName
     * @param  ?string  $insertTimestamp
     * @param  ?bool  $playerAutoplayOn
     * @param  ?bool  $playerPreloadOn
     * @param  ?bool  $playerRemotePlayed
     * @param  ?bool  $usedFullScreen
     * @param  ?bool  $videoStartupFailed
     * @param  ?bool  $viewHasAd
     * @param  ?string  $viewId
     * @param  ?string  $osVersion
     * @param  ?string  $asnName
     * @param  ?int  $asnId
     * @param  ?string  $mediaId
     * @param  ?int  $bufferCount
     * @param  ?int  $bufferFill
     * @param  ?float  $bufferFrequency
     * @param  ?string  $cdn
     * @param  ?string  $city
     * @param  ?string  $continent
     * @param  ?string  $countryCode
     * @param  ?string  $country
     * @param  ?string  $custom1
     * @param  ?string  $custom2
     * @param  ?string  $custom3
     * @param  ?string  $custom4
     * @param  ?string  $custom5
     * @param  ?string  $custom6
     * @param  ?string  $custom7
     * @param  ?string  $custom8
     * @param  ?string  $custom9
     * @param  ?string  $custom10
     * @param  ?string  $latitude
     * @param  ?string  $fpLiveStreamId
     * @param  ?float  $liveStreamLatency
     * @param  ?string  $longitude
     * @param  ?int  $pageLoadTime
     * @param  ?string  $pageContext
     * @param  ?string  $viewPageUrl
     * @param  ?string  $fpPlaybackId
     * @param  ?float  $playbackScore
     * @param  ?string  $errorCode
     * @param  ?string  $errorMessage
     * @param  string|float|null  $playerHeight
     * @param  ?string  $playerInstanceId
     * @param  ?string  $playerLanguage
     * @param  ?string  $fpSDK
     * @param  ?string  $fpSDKVersion
     * @param  ?string  $playerName
     * @param  ?string  $playerPoster
     * @param  ?string  $playerSoftwareVersion
     * @param  ?string  $playerSoftwareName
     * @param  ?string  $videoSourceDomain
     * @param  ?int  $videoSourceDuration
     * @param  ?int  $playerSourceHeight
     * @param  ?string  $videoSourceHostname
     * @param  ?string  $videoSourceStreamType
     * @param  ?string  $videoSourceType
     * @param  ?string  $videoSourceUrl
     * @param  ?int  $playerSourceWidth
     * @param  ?int  $playerInitializationTime
     * @param  ?string  $playerVersion
     * @param  string|float|null  $playerWidth
     * @param  ?float  $renderQualityScore
     * @param  ?float  $bufferRatio
     * @param  ?float  $stabilityScore
     * @param  ?string  $region
     * @param  ?string  $sessionId
     * @param  ?float  $startupScore
     * @param  ?string  $subPropertyId
     * @param  ?int  $videoStartupTime
     * @param  ?string  $updatedTimestamp
     * @param  ?string  $videoContentType
     * @param  ?int  $videoDuration
     * @param  ?string  $videoId
     * @param  ?string  $videoLanguage
     * @param  ?string  $videoSeries
     * @param  ?string  $videoTitle
     * @param  ?float  $avgRequestLatency
     * @param  ?float  $avgRequestThroughput
     * @param  ?string  $drmType
     * @param  ?int  $droppedFrameCount
     * @param  ?string  $viewEnd
     * @param  ?float  $maxDownscaling
     * @param  ?int  $viewMaxPlayheadPosition
     * @param  ?float  $maxRequestLatency
     * @param  ?float  $maxUpscaling
     * @param  ?int  $viewPlayingTime
     * @param  ?int  $viewSeekedCount
     * @param  ?int  $viewSeekedDuration
     * @param  ?string  $viewStart
     * @param  ?int  $viewTotalContentPlaybackTime
     * @param  ?float  $avgDownscaling
     * @param  ?float  $avgUpscaling
     * @param  ?string  $browserName
     * @param  ?string  $browserVersion
     * @param  ?string  $connectiontype
     * @param  ?string  $deviceType
     * @param  ?string  $deviceManufacturer
     * @param  ?string  $deviceModel
     * @param  ?string  $deviceName
     * @param  ?float  $qualityOfExperienceScore
     * @param  ?string  $osName
     * @param  ?string  $userAgent
     * @param  ?string  $viewerId
     * @param  ?int  $totalWatchTime
     * @param  ?float  $averageBitrate
     * @param  ?float  $jumpLatency
     * @param  ?string  $playerResolution
     * @param  ?string  $videoResolution
     * @phpstan-pure
     */
    public function __construct(?string $workspaceId = null, ?array $events = null, ?bool $exitBeforeVideoStart = null, ?string $experimentName = null, ?string $insertTimestamp = null, ?bool $playerAutoplayOn = null, ?bool $playerPreloadOn = null, ?bool $playerRemotePlayed = null, ?bool $usedFullScreen = null, ?bool $videoStartupFailed = null, ?bool $viewHasAd = null, ?string $viewId = null, ?string $osVersion = null, ?string $asnName = null, ?int $asnId = null, ?string $mediaId = null, ?int $bufferCount = null, ?int $bufferFill = null, ?float $bufferFrequency = null, ?string $cdn = null, ?string $city = null, ?string $continent = null, ?string $countryCode = null, ?string $country = null, ?string $custom1 = null, ?string $custom2 = null, ?string $custom3 = null, ?string $custom4 = null, ?string $custom5 = null, ?string $custom6 = null, ?string $custom7 = null, ?string $custom8 = null, ?string $custom9 = null, ?string $custom10 = null, ?string $latitude = null, ?string $fpLiveStreamId = null, ?float $liveStreamLatency = null, ?string $longitude = null, ?int $pageLoadTime = null, ?string $pageContext = null, ?string $viewPageUrl = null, ?string $fpPlaybackId = null, ?float $playbackScore = null, ?string $errorCode = null, ?string $errorMessage = null, string|float|null $playerHeight = null, ?string $playerInstanceId = null, ?string $playerLanguage = null, ?string $fpSDK = null, ?string $fpSDKVersion = null, ?string $playerName = null, ?string $playerPoster = null, ?string $playerSoftwareVersion = null, ?string $playerSoftwareName = null, ?string $videoSourceDomain = null, ?int $videoSourceDuration = null, ?int $playerSourceHeight = null, ?string $videoSourceHostname = null, ?string $videoSourceStreamType = null, ?string $videoSourceType = null, ?string $videoSourceUrl = null, ?int $playerSourceWidth = null, ?int $playerInitializationTime = null, ?string $playerVersion = null, string|float|null $playerWidth = null, ?float $renderQualityScore = null, ?float $bufferRatio = null, ?float $stabilityScore = null, ?string $region = null, ?string $sessionId = null, ?float $startupScore = null, ?string $subPropertyId = null, ?int $videoStartupTime = null, ?string $updatedTimestamp = null, ?string $videoContentType = null, ?int $videoDuration = null, ?string $videoId = null, ?string $videoLanguage = null, ?string $videoSeries = null, ?string $videoTitle = null, ?float $avgRequestLatency = null, ?float $avgRequestThroughput = null, ?string $drmType = null, ?int $droppedFrameCount = null, ?string $viewEnd = null, ?float $maxDownscaling = null, ?int $viewMaxPlayheadPosition = null, ?float $maxRequestLatency = null, ?float $maxUpscaling = null, ?int $viewPlayingTime = null, ?int $viewSeekedCount = null, ?int $viewSeekedDuration = null, ?string $viewStart = null, ?int $viewTotalContentPlaybackTime = null, ?float $avgDownscaling = null, ?float $avgUpscaling = null, ?string $browserName = null, ?string $browserVersion = null, ?string $connectiontype = null, ?string $deviceType = null, ?string $deviceManufacturer = null, ?string $deviceModel = null, ?string $deviceName = null, ?float $qualityOfExperienceScore = null, ?string $osName = null, ?string $userAgent = null, ?string $viewerId = null, ?int $totalWatchTime = null, ?float $averageBitrate = null, ?float $jumpLatency = null, ?string $playerResolution = null, ?string $videoResolution = null)
    {
        $this->workspaceId = $workspaceId;
        $this->events = $events;
        $this->exitBeforeVideoStart = $exitBeforeVideoStart;
        $this->experimentName = $experimentName;
        $this->insertTimestamp = $insertTimestamp;
        $this->playerAutoplayOn = $playerAutoplayOn;
        $this->playerPreloadOn = $playerPreloadOn;
        $this->playerRemotePlayed = $playerRemotePlayed;
        $this->usedFullScreen = $usedFullScreen;
        $this->videoStartupFailed = $videoStartupFailed;
        $this->viewHasAd = $viewHasAd;
        $this->viewId = $viewId;
        $this->osVersion = $osVersion;
        $this->asnName = $asnName;
        $this->asnId = $asnId;
        $this->mediaId = $mediaId;
        $this->bufferCount = $bufferCount;
        $this->bufferFill = $bufferFill;
        $this->bufferFrequency = $bufferFrequency;
        $this->cdn = $cdn;
        $this->city = $city;
        $this->continent = $continent;
        $this->countryCode = $countryCode;
        $this->country = $country;
        $this->custom1 = $custom1;
        $this->custom2 = $custom2;
        $this->custom3 = $custom3;
        $this->custom4 = $custom4;
        $this->custom5 = $custom5;
        $this->custom6 = $custom6;
        $this->custom7 = $custom7;
        $this->custom8 = $custom8;
        $this->custom9 = $custom9;
        $this->custom10 = $custom10;
        $this->latitude = $latitude;
        $this->fpLiveStreamId = $fpLiveStreamId;
        $this->liveStreamLatency = $liveStreamLatency;
        $this->longitude = $longitude;
        $this->pageLoadTime = $pageLoadTime;
        $this->pageContext = $pageContext;
        $this->viewPageUrl = $viewPageUrl;
        $this->fpPlaybackId = $fpPlaybackId;
        $this->playbackScore = $playbackScore;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->playerHeight = $playerHeight;
        $this->playerInstanceId = $playerInstanceId;
        $this->playerLanguage = $playerLanguage;
        $this->fpSDK = $fpSDK;
        $this->fpSDKVersion = $fpSDKVersion;
        $this->playerName = $playerName;
        $this->playerPoster = $playerPoster;
        $this->playerSoftwareVersion = $playerSoftwareVersion;
        $this->playerSoftwareName = $playerSoftwareName;
        $this->videoSourceDomain = $videoSourceDomain;
        $this->videoSourceDuration = $videoSourceDuration;
        $this->playerSourceHeight = $playerSourceHeight;
        $this->videoSourceHostname = $videoSourceHostname;
        $this->videoSourceStreamType = $videoSourceStreamType;
        $this->videoSourceType = $videoSourceType;
        $this->videoSourceUrl = $videoSourceUrl;
        $this->playerSourceWidth = $playerSourceWidth;
        $this->playerInitializationTime = $playerInitializationTime;
        $this->playerVersion = $playerVersion;
        $this->playerWidth = $playerWidth;
        $this->renderQualityScore = $renderQualityScore;
        $this->bufferRatio = $bufferRatio;
        $this->stabilityScore = $stabilityScore;
        $this->region = $region;
        $this->sessionId = $sessionId;
        $this->startupScore = $startupScore;
        $this->subPropertyId = $subPropertyId;
        $this->videoStartupTime = $videoStartupTime;
        $this->updatedTimestamp = $updatedTimestamp;
        $this->videoContentType = $videoContentType;
        $this->videoDuration = $videoDuration;
        $this->videoId = $videoId;
        $this->videoLanguage = $videoLanguage;
        $this->videoSeries = $videoSeries;
        $this->videoTitle = $videoTitle;
        $this->avgRequestLatency = $avgRequestLatency;
        $this->avgRequestThroughput = $avgRequestThroughput;
        $this->drmType = $drmType;
        $this->droppedFrameCount = $droppedFrameCount;
        $this->viewEnd = $viewEnd;
        $this->maxDownscaling = $maxDownscaling;
        $this->viewMaxPlayheadPosition = $viewMaxPlayheadPosition;
        $this->maxRequestLatency = $maxRequestLatency;
        $this->maxUpscaling = $maxUpscaling;
        $this->viewPlayingTime = $viewPlayingTime;
        $this->viewSeekedCount = $viewSeekedCount;
        $this->viewSeekedDuration = $viewSeekedDuration;
        $this->viewStart = $viewStart;
        $this->viewTotalContentPlaybackTime = $viewTotalContentPlaybackTime;
        $this->avgDownscaling = $avgDownscaling;
        $this->avgUpscaling = $avgUpscaling;
        $this->browserName = $browserName;
        $this->browserVersion = $browserVersion;
        $this->connectiontype = $connectiontype;
        $this->deviceType = $deviceType;
        $this->deviceManufacturer = $deviceManufacturer;
        $this->deviceModel = $deviceModel;
        $this->deviceName = $deviceName;
        $this->qualityOfExperienceScore = $qualityOfExperienceScore;
        $this->osName = $osName;
        $this->userAgent = $userAgent;
        $this->viewerId = $viewerId;
        $this->totalWatchTime = $totalWatchTime;
        $this->averageBitrate = $averageBitrate;
        $this->jumpLatency = $jumpLatency;
        $this->playerResolution = $playerResolution;
        $this->videoResolution = $videoResolution;
    }
}