# FastPix PHP SDK

Developer-friendly & type-safe PHP SDK specifically designed to leverage the FastPix platform API.

# Introduction

The FastPix PHP SDK simplifies integration with the FastPix platform. This SDK is designed for secure and efficient communication with the FastPix API, enabling easy management of media uploads, live streaming, and simulcasting.

<!-- Start Summary [summary] -->
## Key Features

FASTPIX API'S: FastPix provides a comprehensive set of APIs that enable developers to manage both **on-demand media (video/audio)** and **live streaming experiences**, with built-in security features through **cryptographic signing keys**. These APIs cover the full lifecycle of content creation, management, distribution, playback, and secure access, making them ideal for building scalable video-first applications.
### Media APIs (Video & Audio on Demand)
The **Media APIs** allow developers to create, retrieve, update, and delete media files, as well as manage metadata, playback settings, and additional tracks such as audio or subtitles. With these endpoints, developers can:
- Upload videos directly or create media from URLs.   - Manage playback permissions and configure playback IDs.   - Add multilingual audio or subtitle tracks for global audiences.   - Build robust video-on-demand (VOD) and audio-on-demand (AOD) libraries.  
**Use case scenarios**   - **Video-on-Demand Platforms:** Manage large content libraries for streaming services.   - **E-Learning Solutions:** Upload and organize lecture videos, metadata, and playback settings.   - **Multilingual Content Delivery:** Add multiple language tracks or subtitles to serve global users.  
### Live Stream APIs
The **Live Stream APIs** simplify the process of creating, managing, and distributing live content. Developers can initiate broadcasts, configure stream settings, and extend streams to external platforms through simulcasting. These endpoints also support real-time interaction and customization of live events.
- Start and manage live broadcasts programmatically.   - Control stream metadata, privacy, and playback options.   - Simulcast to platforms like YouTube, Facebook, or Twitch.   - Update stream details and manage live playback IDs in real time.  
**Use case scenarios**   - **Event Broadcasting:** Enable organizers to set up live streams for conferences, concerts, or webinars.   - **Creator Platforms:** Provide streamers with tools for broadcasting gameplay, tutorials, or vlogs with simulcasting support.   - **Corporate Streaming:** Deliver secure internal town halls or meetings with privacy and playback controls.  
### Video Data APIs
The **Video Data APIs** Provide insights into viewer interactions, performance metrics, and playback errors to optimize content delivery and user experience.

 - Track video views, unique viewers, and engagement metrics
 - Identify top-performing content and usage patterns
 - Break down data by browser, device, or geography
 - Detect playback errors and performance issues
 - Enable data-driven content strategy decisions
 
 **Use case scenarios** 
 - Analytics Dashboards: Monitor performance across content libraries
 - Quality Monitoring: Diagnose and resolve playback issues
 - Content Strategy Optimization: Identify high-value content
 - User Behavior Insights: Understand audience interactions

### Signing Keys
FastPix also provides endpoints for managing **cryptographic signing keys**, which are essential for securely signing and verifying tokens, such as JSON Web Tokens (JWTs). These keys are critical for authenticating and authorizing API requests, as well as for protecting access to media assets.
- **Private key:** Used to create digital signatures (kept secret).   - **Public key:** Used to verify digital signatures (shared for verification).  
By rotating and managing signing keys regularly, developers can maintain strong security practices and prevent unauthorized access.  
**Use case scenarios**   - **Token-based authentication:** Validate user access to premium or subscription-based content.   - **Key rotation:** Regularly rotate keys to reduce risk of compromise.   - **Protect intellectual property:** Prevent unauthorized distribution of valuable media assets.   - **Control usage:** Restrict access to specific users, groups, or contexts.   - **Prevent tampering:** Ensure requested assets have not been modified.   - **Time-bound access:** Enable signed URLs with expiration for controlled viewing windows.
<!-- End Summary [summary] -->

# Prerequisites:
- PHP 7.4 or later
- Composer package manager
- FastPix API credentials (Access Token and Secret Key)

<!-- Start Table of Contents [toc] -->
## Table of Contents
<!-- $toc-max-depth=2 -->
* [fastpix/sdk](#fastpixsdk)
  * [SDK Installation](#sdk-installation)
  * [Initialization](#initialization)
  * [SDK Example Usage](#sdk-example-usage)
  * [Available Resources and Operations](#available-resources-and-operations)
  * [Retries](#retries)
  * [Error Handling](#error-handling)
  * [Server Selection](#server-selection)
  * [Detailed Usage](#detailed-usage)
  * [Maturity](#maturity)
  * [Contributions](#contributions)

<!-- End Table of Contents [toc] -->

<!-- Start SDK Installation [installation] -->
## SDK Installation

> [!TIP]
> To finish publishing your SDK you must [run your first generation action](https://www.speakeasy.com/docs/github-setup#step-by-step-guide).


The SDK relies on [Composer](https://getcomposer.org/) to manage its dependencies.

To install the SDK first add the below to your `composer.json` file:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/FastPix/fastpix-php.git"
        }
    ],
    "require": {
        "fastpix/sdk": "*"
    }
}
```



Then run the following command:

```bash
composer update
```
<!-- End SDK Installation [installation] -->


<!-- Start Initialization  -->
## Initialization

You can set the security parameters through the `security` builder method when initializing the SDK client instance. For example:

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = SDK::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();
```
<!-- End Authentication [security] -->

<!-- Start SDK Example Usage [usage] -->
## SDK Example Usage

### Example

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = SDK::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

$request = new Components\CreateMediaRequest(
    inputs: [
        new Components\VideoInput(
            type: 'video',
            url: 'https://static.fastpix.io/sample.mp4',
        ),
    ],
    metadata: [
        'key1' => 'value1',
    ],
    accessPolicy: Components\CreateMediaRequestAccessPolicy::Public,
);

$response = $sdk->inputVideo->createMedia(
    request: $request
);

if ($response->createMediaSuccessResponse !== null) {
    // handle response
}
```
<!-- End SDK Example Usage [usage] -->

## Available Resources and Operations

<details open>
<summary>Available methods</summary>

### [dimensions](docs/sdks/dimensions/README.md)

* [listDimensions](docs/sdks/dimensions/README.md#listdimensions) - List the dimensions
* [getDimensionsData](docs/sdks/dimensions/README.md#getdimensionsdata) - Get dimensions data

### [drmConfigurations](docs/sdks/drmconfigurations/README.md)

* [getDrmConfiguration](docs/sdks/drmconfigurations/README.md#getdrmconfiguration) - Get list of DRM configuration IDs
* [getDrmConfigurationById](docs/sdks/drmconfigurations/README.md#getdrmconfigurationbyid) - Get DRM configuration by ID

### [errors](docs/sdks/errors/README.md)

* [getErrorData](docs/sdks/errors/README.md#geterrordata) - Get error data

### [inputVideo](docs/sdks/inputvideo/README.md)

* [createMedia](docs/sdks/inputvideo/README.md#createmedia) - Create media from URL
* [directUpload](docs/sdks/inputvideo/README.md#directupload) - Upload media from device

### [inVideoAIFeatures](docs/sdks/invideoaifeatures/README.md)

* [updateMediaSummary](docs/sdks/invideoaifeatures/README.md#updatemediasummary) - Generate video summary
* [updateMediaChapters](docs/sdks/invideoaifeatures/README.md#updatemediachapters) - Generate video chapters
* [updateMediaNamedEntities](docs/sdks/invideoaifeatures/README.md#updatemedianamedentities) - Generate named entities
* [updateMediaModeration](docs/sdks/invideoaifeatures/README.md#updatemediamoderation) - Enable video moderation

### [livePlayback](docs/sdks/liveplayback/README.md)

* [createPlaybackIdOfStream](docs/sdks/liveplayback/README.md#createplaybackidofstream) - Create a playbackId
* [deletePlaybackIdOfStream](docs/sdks/liveplayback/README.md#deleteplaybackidofstream) - Delete a playbackId
* [getLiveStreamPlaybackId](docs/sdks/liveplayback/README.md#getlivestreamplaybackid) - Get playbackId details

### [manageLiveStream](docs/sdks/managelivestream/README.md)

* [getAllStreams](docs/sdks/managelivestream/README.md#getallstreams) - Get all live streams
* [getLiveStreamViewerCountById](docs/sdks/managelivestream/README.md#getlivestreamviewercountbyid) - Get stream views by ID
* [getLiveStreamById](docs/sdks/managelivestream/README.md#getlivestreambyid) - Get stream by ID
* [deleteLiveStream](docs/sdks/managelivestream/README.md#deletelivestream) - Delete a stream
* [updateLiveStream](docs/sdks/managelivestream/README.md#updatelivestream) - Update a stream
* [enableLiveStream](docs/sdks/managelivestream/README.md#enablelivestream) - Enable a stream
* [disableLiveStream](docs/sdks/managelivestream/README.md#disablelivestream) - Disable a stream
* [completeLiveStream](docs/sdks/managelivestream/README.md#completelivestream) - Complete a stream

### [manageVideos](docs/sdks/managevideos/README.md)

* [listMedia](docs/sdks/managevideos/README.md#listmedia) - Get list of all media
* [listLiveClips](docs/sdks/managevideos/README.md#listliveclips) - Get all clips of a live stream
* [getMedia](docs/sdks/managevideos/README.md#getmedia) - Get a media by ID
* [updatedMedia](docs/sdks/managevideos/README.md#updatedmedia) - Update a media by ID
* [deleteMedia](docs/sdks/managevideos/README.md#deletemedia) - Delete a media by ID
* [addMediaTrack](docs/sdks/managevideos/README.md#addmediatrack) - Add audio / subtitle track
* [cancelUpload](docs/sdks/managevideos/README.md#cancelupload) - Cancel ongoing upload
* [updateMediaTrack](docs/sdks/managevideos/README.md#updatemediatrack) - Update audio / subtitle track
* [deleteMediaTrack](docs/sdks/managevideos/README.md#deletemediatrack) - Delete audio / subtitle track
* [generateSubtitleTrack](docs/sdks/managevideos/README.md#generatesubtitletrack) - Generate track subtitle
* [updatedSourceAccess](docs/sdks/managevideos/README.md#updatedsourceaccess) - Update the source access of a media by ID
* [updatedMp4Support](docs/sdks/managevideos/README.md#updatedmp4support) - Update the mp4Support of a media by ID
* [retrieveMediaInputInfo](docs/sdks/managevideos/README.md#retrievemediainputinfo) - Get info of media inputs
* [listUploads](docs/sdks/managevideos/README.md#listuploads) - Get all unused upload URLs
* [getMediaClips](docs/sdks/managevideos/README.md#getmediaclips) - Get all clips of a media
* [updateMediaSummary](docs/sdks/managevideos/README.md#updatemediasummary) - Update media summary
* [updateMediaChapters](docs/sdks/managevideos/README.md#updatemediachapters) - Update media chapters
* [updateMediaNamedEntities](docs/sdks/managevideos/README.md#updatemedianamedentities) - Update named entities
* [updateMediaModeration](docs/sdks/managevideos/README.md#updatemediamoderation) - Update moderation settings

### [metrics](docs/sdks/metrics/README.md)

* [getMetricsBreakdown](docs/sdks/metrics/README.md#getmetricsbreakdown) - Get metrics breakdown
* [getVideoMetrics](docs/sdks/metrics/README.md#getvideometrics) - Get overall video metrics
* [getMetricsTimeseries](docs/sdks/metrics/README.md#getmetricstimeseries) - Get metrics timeseries
* [getMetricsComparison](docs/sdks/metrics/README.md#getmetricscomparison) - Get metrics comparison

### [playback](docs/sdks/playback/README.md)

* [createMediaPlaybackId](docs/sdks/playback/README.md#createmediaplaybackid) - Create a playback ID
* [deleteMediaPlaybackId](docs/sdks/playback/README.md#deletemediaplaybackid) - Delete a playback ID
* [getPlaybackId](docs/sdks/playback/README.md#getplaybackid) - Get a playback ID

### [playlist](docs/sdks/playlist/README.md)

* [createAPlaylist](docs/sdks/playlist/README.md#createaplaylist) - Create a new playlist
* [getAllPlaylists](docs/sdks/playlist/README.md#getallplaylists) - Get all playlists
* [getPlaylistById](docs/sdks/playlist/README.md#getplaylistbyid) - Get a playlist by ID
* [updateAPlaylist](docs/sdks/playlist/README.md#updateaplaylist) - Update a playlist by ID
* [deleteAPlaylist](docs/sdks/playlist/README.md#deleteaplaylist) - Delete a playlist by ID
* [addMediaToPlaylist](docs/sdks/playlist/README.md#addmediatoplaylist) - Add media to a playlist by ID
* [replacePlaylistMedia](docs/sdks/playlist/README.md#replaceplaylistmedia) - Replace playlist media
* [changeMediaOrderInPlaylist](docs/sdks/playlist/README.md#changemediaorderinplaylist) - Change media order in a playlist by ID
* [deleteMediaFromPlaylist](docs/sdks/playlist/README.md#deletemediafromplaylist) - Delete media in a playlist by ID


### [signingKeys](docs/sdks/signingkeys/README.md)

* [createSigningKey](docs/sdks/signingkeys/README.md#createsigningkey) - Create a signing key
* [getAllSigningKeys](docs/sdks/signingkeys/README.md#getallsigningkeys) - Get list of signing key
* [deleteSigningKey](docs/sdks/signingkeys/README.md#deletesigningkey) - Delete a signing key
* [getSigningKeyById](docs/sdks/signingkeys/README.md#getsigningkeybyid) - Get signing key by ID

### [simulcastStream](docs/sdks/simulcaststream/README.md)

* [createSimulcastOfStream](docs/sdks/simulcaststream/README.md#createsimulcastofstream) - Create a simulcast
* [listSimulcastsOfStream](docs/sdks/simulcaststream/README.md#listsimulcastsofstream) - List simulcast targets
* [deleteSimulcastOfStream](docs/sdks/simulcaststream/README.md#deletesimulcastofstream) - Delete a simulcast
* [getSpecificSimulcastOfStream](docs/sdks/simulcaststream/README.md#getspecificsimulcastofstream) - Get a specific simulcast
* [updateSpecificSimulcastOfStream](docs/sdks/simulcaststream/README.md#updatespecificsimulcastofstream) - Update a simulcast

### [startLiveStream](docs/sdks/startlivestream/README.md)

* [createNewStream](docs/sdks/startlivestream/README.md#createnewstream) - Create a new stream
* [getStreamById](docs/sdks/startlivestream/README.md#getstreambyid) - Get stream by ID
* [updateStream](docs/sdks/startlivestream/README.md#updatestream) - Update stream

### [views](docs/sdks/views/README.md)

* [getViewsData](docs/sdks/views/README.md#getviewsdata) - Get views data
* [getViewsByDimension](docs/sdks/views/README.md#getviewsbydimension) - Get views by dimension
* [getViewsByTopContent](docs/sdks/views/README.md#getviewsbytopcontent) - Get top content views
* [getTimeseriesViews](docs/sdks/views/README.md#gettimeseriesviews) - Get concurrent viewers timeseries
* [getViewsCount](docs/sdks/views/README.md#getviewscount) - Get views count

</details>
<!-- End Available Resources and Operations [operations] -->

<!-- Start Retries [retries] -->
## Retries

Some of the endpoints in this SDK support retries. If you use the SDK without any configuration, it will fall back to the default retry strategy provided by the API. However, the default retry strategy can be overridden on a per-operation basis, or across the entire SDK.

To change the default retry strategy for a single API call, simply provide an `Options` object built with a `RetryConfig` object to the call:
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\Retry;

$sdk = SDK::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

$request = new Components\CreateMediaRequest(
    inputs: [
        new Components\VideoInput(
            type: 'video',
            url: 'https://static.fastpix.io/sample.mp4',
        ),
    ],
    metadata: [
        'key1' => 'value1',
    ],
    accessPolicy: Components\CreateMediaRequestAccessPolicy::Public,
);

$response = $sdk->inputVideo->createMedia(
    request: $request,
    options: Utils\Options->builder()->setRetryConfig(
        new Retry\RetryConfigBackoff(
            initialIntervalMs: 1000,
            maxIntervalMs: 50000,
            exponent: 1.1,
            maxElapsedTimeMs: 100000,
            retryConnectionErrors: false,
        ))->build()
);

if ($response->createMediaSuccessResponse !== null) {
    // handle response
}
```

If you'd like to override the default retry strategy for all operations that support retries, you can pass a `RetryConfig` object to the `SDKBuilder->setRetryConfig` function when initializing the SDK:
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Utils\Retry;

$sdk = SDK::builder()
    ->setRetryConfig(
        new Retry\RetryConfigBackoff(
            initialIntervalMs: 1000,
            maxIntervalMs: 50000,
            exponent: 1.1,
            maxElapsedTimeMs: 100000,
            retryConnectionErrors: false,
        )
  )
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

$request = new Components\CreateMediaRequest(
    inputs: [
        new Components\VideoInput(
            type: 'video',
            url: 'https://static.fastpix.io/sample.mp4',
        ),
    ],
    metadata: [
        'key1' => 'value1',
    ],
    accessPolicy: Components\CreateMediaRequestAccessPolicy::Public,
);

$response = $sdk->inputVideo->createMedia(
    request: $request
);

if ($response->createMediaSuccessResponse !== null) {
    // handle response
}
```
<!-- End Retries [retries] -->

<!-- Start Error Handling [errors] -->
## Error Handling

Handling errors in this SDK should largely match your expectations. All operations return a response object or throw an exception.

By default an API error will raise a `Errors\APIException` exception, which has the following properties:

| Property       | Type                                    | Description           |
|----------------|-----------------------------------------|-----------------------|
| `$message`     | *string*                                | The error message     |
| `$statusCode`  | *int*                                   | The HTTP status code  |
| `$rawResponse` | *?\Psr\Http\Message\ResponseInterface*  | The raw HTTP response |
| `$body`        | *string*                                | The response content  |

When custom error responses are specified for an operation, the SDK may also throw their associated exception. You can refer to respective *Errors* tables in SDK docs for more details on possible exception types for each operation. For example, the `createMedia` method throws the following exceptions:

| Error Type                        | Status Code | Content Type     |
| --------------------------------- | ----------- | ---------------- |
| Errors\BadRequestException        | 400         | application/json |
| Errors\InvalidPermissionException | 401         | application/json |
| Errors\ForbiddenException         | 403         | application/json |
| Errors\ValidationErrorResponse    | 422         | application/json |
| Errors\APIException               | 4XX, 5XX    | \*/\*            |

### Example

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Errors;

$sdk = SDK::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

try {
    $request = new Components\CreateMediaRequest(
        inputs: [
            new Components\VideoInput(
                type: 'video',
                url: 'https://static.fastpix.io/sample.mp4',
            ),
        ],
        metadata: [
            'key1' => 'value1',
        ],
        accessPolicy: Components\CreateMediaRequestAccessPolicy::Public,
    );

    $response = $sdk->inputVideo->createMedia(
        request: $request
    );

    if ($response->createMediaSuccessResponse !== null) {
        // handle response
    }
} catch (Errors\BadRequestExceptionThrowable $e) {
    // handle $e->$container data
    throw $e;
} catch (Errors\InvalidPermissionExceptionThrowable $e) {
    // handle $e->$container data
    throw $e;
} catch (Errors\ForbiddenExceptionThrowable $e) {
    // handle $e->$container data
    throw $e;
} catch (Errors\ValidationErrorResponseThrowable $e) {
    // handle $e->$container data
    throw $e;
} catch (Errors\APIException $e) {
    // handle default exception
    throw $e;
}
```
<!-- End Error Handling [errors] -->

<!-- Start Server Selection [server] -->
## Server Selection

### Override Server URL Per-Client

The default server can be overridden globally using the `setServerUrl(string $serverUrl)` builder method when initializing the SDK client instance. For example:
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = SDK::builder()
    ->setServerURL('https://api.fastpix.io/v1/')
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

$request = new Components\CreateMediaRequest(
    inputs: [
        new Components\VideoInput(
            type: 'video',
            url: 'https://static.fastpix.io/sample.mp4',
        ),
    ],
    metadata: [
        'key1' => 'value1',
    ],
    accessPolicy: Components\CreateMediaRequestAccessPolicy::Public,
);

$response = $sdk->inputVideo->createMedia(
    request: $request
);

if ($response->createMediaSuccessResponse !== null) {
    // handle response
}
```
<!-- End Server Selection [server] -->

<!-- Placeholder for Future Speakeasy SDK Sections -->

# Detailed Usage

For a complete understanding of each API's functionality, including request and response details, parameter descriptions, and additional examples, please refer to the [FastPix API Reference](https://docs.fastpix.io/reference/signingkeys-overview).

The API reference provides comprehensive documentation for all available endpoints and features, ensuring developers can integrate and utilize FastPix APIs efficiently.

