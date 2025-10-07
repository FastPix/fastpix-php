<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


class ViewsList
{
    /**
     * The unique identifier for the viewing session of the user.
     *
     *
     *
     * @var string $viewId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewId')]
    public string $viewId;

    /**
     * Operating System signifies the software platform utilized by the viewer
     *
     *
     *
     * @var ?string $operatingSystem
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('operatingSystem')]
    public ?string $operatingSystem;

    /**
     * The browser name of the viewer.
     *
     *
     *
     * @var ?string $application
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('application')]
    public ?string $application;

    /**
     * The start timestamp of the video view.
     *
     *
     *
     * @var ?string $viewStartTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewStartTime')]
    public ?string $viewStartTime;

    /**
     * The end timestamp of the video view.
     *
     *
     *
     * @var ?string $viewEndTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewEndTime')]
    public ?string $viewEndTime;

    /**
     * The title of the Video.
     *
     *
     *
     * @var ?string $videoTitle
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('videoTitle')]
    public ?string $videoTitle;

    /**
     * Country of the viewer.
     *
     *
     *
     * @var ?string $country
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('country')]
    public ?string $country;

    /**
     * The code which represents specific issues or failures that occur during playback. These can be implementation specific.
     *
     *
     *
     * @var ?string $errorCode
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('errorCode')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $errorCode = null;

    /**
     * The notifications or messages that inform users or developers about issues or failures that have occurred during the playback representing error codes.
     *
     *
     *
     * @var ?string $errorMessage
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('errorMessage')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?string $errorMessage = null;

    /**
     * The unique identifier which identifies each type of error that occurs.
     *
     *
     *
     * @var ?int $errorId
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('errorId')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?int $errorId = null;

    /**
     * The watch time represents the time spent watching the video including staruptime, playback time ,buffering time.
     *
     *
     *
     * @var ?float $viewWatchTime
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('viewWatchTime')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $viewWatchTime = null;

    /**
     * The viewer experience encapsulated in the form of score while watching the video.
     *
     *
     *
     * @var ?float $qoeScore
     */
    #[\Speakeasy\Serializer\Annotation\SerializedName('QoeScore')]
    #[\Speakeasy\Serializer\Annotation\SkipWhenNull]
    public ?float $qoeScore = null;

    /**
     * @param  string  $viewId
     * @param  ?string  $operatingSystem
     * @param  ?string  $application
     * @param  ?string  $viewStartTime
     * @param  ?string  $viewEndTime
     * @param  ?string  $videoTitle
     * @param  ?string  $country
     * @param  ?string  $errorCode
     * @param  ?string  $errorMessage
     * @param  ?int  $errorId
     * @param  ?float  $viewWatchTime
     * @param  ?float  $qoeScore
     * @phpstan-pure
     */
    public function __construct(string $viewId, ?string $operatingSystem = null, ?string $application = null, ?string $viewStartTime = null, ?string $viewEndTime = null, ?string $videoTitle = null, ?string $country = null, ?string $errorCode = null, ?string $errorMessage = null, ?int $errorId = null, ?float $viewWatchTime = null, ?float $qoeScore = null)
    {
        $this->viewId = $viewId;
        $this->operatingSystem = $operatingSystem;
        $this->application = $application;
        $this->viewStartTime = $viewStartTime;
        $this->viewEndTime = $viewEndTime;
        $this->videoTitle = $videoTitle;
        $this->country = $country;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->errorId = $errorId;
        $this->viewWatchTime = $viewWatchTime;
        $this->qoeScore = $qoeScore;
    }
}