# FastPix PHP SDK

Developer-friendly & type-safe PHP SDK for the FastPix platform API


## Introduction

The FastPix PHP SDK simplifies integration with the FastPix platform. It provides a clean, PHP 8+ interface for secure and efficient communication with the FastPix API, enabling easy management of media uploads, live streaming, on‑demand content, playlists, video analytics, and signing keys for secure access and token management. It is intended for use with PHP 8.2 and above.

## Prerequisites

### Environment and Version Support

| Requirement | Version | Description |
|---|---:|---|
| PHP | `8.2+` | Core runtime environment |
| Composer | `Latest` | Package manager for dependencies |
| Internet | `Required` | API communication and authentication |

> **Pro Tip:** We recommend using PHP 8.3+ for optimal performance and the latest language features.

### Getting Started with FastPix

To get started with the FastPix PHP SDK, ensure you have the following:

- The FastPix APIs are authenticated using a **Username** and a **Password**. You must generate these credentials to use the SDK.
- Follow the steps in the [Authentication with Basic Auth](https://fastpix.com/docs/get-started/overview) guide to obtain your credentials.

### Environment Variables (Optional)

Configure your FastPix credentials using environment variables for enhanced security and convenience:

```bash
# Set your FastPix credentials
export FASTPIX_USERNAME="your-access-token"
export FASTPIX_PASSWORD="your-secret-key"
```

> **Security Note:** Never commit your credentials to version control. Use environment variables or secure credential management systems.

## Table of Contents

* [FastPix PHP SDK](#fastpix-php-sdk)
  * [Setup](#setup)
  * [Example Usage](#example-usage)
  * [Available Resources and Operations](#available-resources-and-operations)
  * [Error Handling](#error-handling)
  * [Server Selection](#server-selection)
  * [Development](#development)

## Setup

### Installation

The SDK relies on [Composer](https://getcomposer.org/) to manage its dependencies.

Add the SDK to your project:

```json
{
    "require": {
        "fastpix/sdk": "*"
    }
}
```

Then run:

```bash
composer update
```

If you host the package in a private repository, add the repository to your `composer.json`:

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

### Imports

Use the SDK via Composer’s autoload and the FastPix namespaces:

```php
<?php

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;
```

### Initialization

Initialize the FastPix SDK with your credentials:

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();
```

Or using environment variables:

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$username = getenv('FASTPIX_USERNAME') ?: 'your-access-token';
$password = getenv('FASTPIX_PASSWORD') ?: 'your-secret-key';

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(
        new Components\Security(
            username: $username,
            password: $password,
        )
    )
    ->build();
```

## Example Usage

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

try {
    $sdk = Sdk\Fastpixsdk::builder()
        ->setSecurity(
            new Components\Security(
                username: 'your-access-token',
                password: 'your-secret-key',
            )
        )
        ->build();

    $request = new Components\CreateMediaRequest(
        inputs: [
            new Components\PullVideoInput(),
        ],
        metadata: [
            'key1' => 'value1',
        ],
    );

    $response = $sdk->inputVideo->createMedia(
    request: $request
);

    if ($response->statusCode >= 200 && $response->statusCode < 300) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo ($decoded !== null ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $rawBody) . "\n";
    } else {
        $errorPayload = $response->defaultError ?? $response->error ?? null;
        if ($errorPayload !== null) {
            $errorResponse = json_decode(json_encode($errorPayload), true);
            echo json_encode($errorResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        } else {
            echo json_encode(['message' => 'No response data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        }
    }
} catch (\Exception $e) {
    // Extract API error response
    $errorBody = null;
    if (property_exists($e, 'body') && property_exists($e, 'statusCode')) {
        $body = $e->body;
        $errorBody = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorBody = $body;
        }
    } elseif (method_exists($e, 'getResponse')) {
        $response = $e->getResponse();
        if ($response !== null) {
            $body = (string)$response->getBody();
            $errorBody = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorBody = $body;
            }
        }
    }
    
    // Output API error response
    if ($errorBody !== null) {
        echo json_encode($errorBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
    exit(1);
}
```

## Available Resources and Operations

Comprehensive PHP SDK for FastPix platform integration with full API coverage.

### Media API

Upload, manage, and transform video content with comprehensive media management capabilities.

For detailed documentation, see [FastPix Video on Demand Overview](https://fastpix.com/docs/get-started/overview).

#### Input Video
- [Create from URL](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/inputvideo/README.md#createmedia) - Upload video content from external URL
- [Upload from Device](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/inputvideo/README.md#directuploadvideomedia) - Upload video files directly from device

#### Manage Videos
- [List All Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#listmedia) - Retrieve complete list of all media files
- [Get Media by ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#getmedia) - Get detailed information for specific media
- [Update Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#updatedmedia) - Modify media metadata and settings
- [Delete Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#deletemedia) - Remove media files from library
- [Cancel Upload](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#cancelupload) - Stop ongoing media upload process
- [Get Input Info](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#retrievemediainputinfo) - Retrieve detailed input information
- [List Uploads](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#listuploads) - Get all available upload URLs
- [Get Media Summary](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#getmediasummary) - Get summary for a media
- [List Live Clips](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#listliveclips) - List live clips for a livestream

#### Playback
- [Create Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#createmediaplaybackid) - Generate secure playback identifier
- [List Playback IDs](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#listplaybackids) - List all playback IDs for a media
- [Get Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#getplaybackid) - Retrieve playback configuration details
- [Delete Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#deletemediaplaybackid) - Remove playback access
- [Update Domain Restrictions](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#updatedomainrestrictions) - Update domain allow/deny list for playback
- [Update User Agent Restrictions](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#updateuseragentrestrictions) - Update user-agent allow/deny list for playback

#### Playlist
- [Create Playlist](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#createaplaylist) - Create new video playlist
- [List Playlists](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#getallplaylists) - Get all available playlists
- [Get Playlist](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#getplaylistbyid) - Retrieve specific playlist details
- [Update Playlist](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#updateaplaylist) - Modify playlist settings and metadata
- [Delete Playlist](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#deleteaplaylist) - Remove playlist from library
- [Add Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#addmediatoplaylist) - Add media items to playlist
- [Reorder Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#changemediaorderinplaylist) - Change order of media in playlist
- [Remove Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#deletemediafromplaylist) - Remove media from playlist

#### Signing Keys
- [Create Key](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/signingkeys/README.md#createsigningkey) - Generate new signing key pair
- [List Keys](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/signingkeys/README.md#listsigningkeys) - Get all available signing keys
- [Delete Key](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/signingkeys/README.md#deletesigningkey) - Remove signing key from system
- [Get Key](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/signingkeys/README.md#getsigningkeybyid) - Retrieve specific signing key details

#### DRM Configurations
- [List DRM Configs](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/drmconfigurations/README.md#getdrmconfiguration) - Get all DRM configuration options
- [Get DRM Config](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/drmconfigurations/README.md#getdrmconfigurationbyid) - Retrieve specific DRM configuration

### Live API

Stream, manage, and transform live video content with real-time broadcasting capabilities.

For detailed documentation, see [FastPix Live Stream Overview](https://fastpix.com/docs/live-stream-api/overview).

#### Start Live Stream
- [Create Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/startlivestream/README.md#createnewstream) - Initialize new live streaming session with DVR mode support

#### Manage Live Stream
- [List Streams](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#getallstreams) - Retrieve all active live streams
- [Get Viewer Count](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#getlivestreamviewercountbyid) - Get real-time viewer statistics
- [Get Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#getlivestreambyid) - Retrieve detailed stream information
- [Delete Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#deletelivestream) - Terminate and remove live stream
- [Update Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#updatelivestream) - Modify stream settings and configuration
- [Enable Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#enablelivestream) - Activate live streaming
- [Disable Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#disablelivestream) - Pause live streaming
- [Complete Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#completelivestream) - Finalize and archive stream

#### Live Playback
- [Create Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/liveplayback/README.md#createplaybackidofstream) - Generate secure live playback access
- [Delete Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/liveplayback/README.md#deleteplaybackidofstream) - Revoke live playback access
- [Get Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/liveplayback/README.md#getlivestreamplaybackid) - Retrieve live playback configuration

#### Simulcast Stream
- [Create Simulcast](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/simulcaststream/README.md#createsimulcastofstream) - Set up multi-platform streaming
- [Delete Simulcast](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/simulcaststream/README.md#deletesimulcastofstream) - Remove simulcast configuration
- [Get Simulcast](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/simulcaststream/README.md#getspecificsimulcastofstream) - Retrieve simulcast settings
- [Update Simulcast](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/simulcaststream/README.md#updatespecificsimulcastofstream) - Modify simulcast parameters

### Video Data API

Monitor video performance and quality with comprehensive analytics and real-time metrics.

For detailed documentation, see [FastPix Video Data Overview](https://fastpix.com/docs/video-data-api/overview).

#### Metrics
- [List Breakdown Values](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/metrics/README.md#listbreakdownvalues) - Get detailed breakdown of metrics by dimension
- [List Overall Values](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/metrics/README.md#listoverallvalues) - Get aggregated metric values across all content
- [Get Timeseries Data](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/metrics/README.md#gettimeseriesdata) - Retrieve time-based metric trends and patterns

#### Views
- [List Video Views](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/views/README.md#listvideoviews) - Get comprehensive list of video viewing sessions
- [Get View Details](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/views/README.md#getvideoviewdetails) - Retrieve detailed information about specific video views
- [List Top Content](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/views/README.md#listbytopcontent) - Find your most popular and engaging content
- [Get Concurrent Viewers](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/metrics/README.md#listcomparisonvalues) - Monitor real-time viewer counts over time

#### Dimensions
- [List Dimensions](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/dimensions/README.md#listdimensions) - Get available data dimensions for filtering and analysis
- [List Filter Values](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/dimensions/README.md#listfiltervaluesfordimension) - Get specific values for a particular dimension

#### Errors
- [List Errors](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/errors/README.md#listerrors) - List playback errors for diagnostics and monitoring

### Transformations

Transform and enhance your video content with powerful AI and editing capabilities.

#### In-Video AI Features

Enhance video content with AI-powered features including moderation, summarization, and intelligent categorization.

- [Update Summary](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/invideoaifeatures/README.md#updatemediasummary) - Create AI-generated video summaries
- [Create Chapters](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/invideoaifeatures/README.md#updatemediachapters) - Automatically generate video chapter markers
- [Extract Entities](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/invideoaifeatures/README.md#updatemedianamedentities) - Identify and extract named entities from content
- [Enable Moderation](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/invideoaifeatures/README.md#updatemediamoderation) - Activate content moderation and safety checks

#### Media Clips

- [Get Media Clips](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#getmediaclips) - Retrieve all clips associated with a source media

#### Subtitles

- [Generate Subtitles](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#generatesubtitletrack) - Create automatic subtitles for media

#### Media Tracks

- [Add Track](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#addmediatrack) - Add audio or subtitle tracks to media
- [Update Track](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#updatemediatrack) - Modify existing audio or subtitle tracks
- [Delete Track](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#deletemediatrack) - Remove audio or subtitle tracks

#### Access Control

- [Update Source Access](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#updatedsourceaccess) - Control access permissions for media source

#### Format Support

- [Update MP4 Support](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#updatedmp4support) - Configure MP4 download capabilities

## Error Handling

All operations return a response object or throw an exception. By default, an API error will raise an `Errors\APIException` (or operation-specific error types).

| Property       | Type                                    | Description           |
|----------------|-----------------------------------------|-----------------------|
| `$message`     | *string*                                | The error message     |
| `$statusCode`  | *int*                                   | The HTTP status code  |
| `$rawResponse` | *?\Psr\Http\Message\ResponseInterface*  | The raw HTTP response |
| `$body`        | *string*                                | The response content  |

### Example

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

try {
    $request = new Components\CreateMediaRequest(
        inputs: [new Components\PullVideoInput(url: 'https://static.fastpix.com/fp-sample-video.mp4')],
        metadata: ['key1' => 'value1'],
    );

    $response = $sdk->inputVideo->createMedia(request: $request);

    if ($response->createMediaSuccessResponse !== null) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
} catch (\FastPix\Sdk\Models\Errors\APIException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Status: " . $e->statusCode . "\n";
    echo "Body: " . $e->body . "\n";
}
```

Refer to the *Errors* tables in each operation’s SDK doc for possible exception types.

## Server Selection

### Override Server URL Per-Client

Override the default server by passing a URL when building the SDK:

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\Fastpixsdk::builder()
    ->setServerUrl('https://api.fastpix.com/v1/')
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();
```

## Development

This PHP SDK is programmatically generated from our API specifications. Any manual modifications to internal files will be overwritten during subsequent generation cycles.

We value community contributions and feedback. Feel free to submit pull requests or open issues with your suggestions, and we'll do our best to include them in future releases.

### Detailed Usage

For comprehensive understanding of each API's functionality, including detailed request and response specifications, parameter descriptions, and additional examples, please refer to the [FastPix API Reference](https://fastpix.com/docs/product-os-api/overview).

The API reference offers complete documentation for all available endpoints and features, enabling developers to integrate and leverage FastPix APIs effectively.

---
