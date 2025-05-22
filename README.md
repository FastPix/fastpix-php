# FastPix PHP SDK

Developer-friendly & type-safe PHP SDK specifically designed to leverage the FastPix platform API.

# Introduction

The FastPix PHP SDK simplifies integration with the FastPix platform. This SDK is designed for secure and efficient communication with the FastPix API, enabling easy management of media uploads, live streaming, and simulcasting.

# Key Features

- ## Media API
  - **Upload Media**: Upload media files seamlessly from URLs or devices
  - **Manage Media**: Perform operations such as listing, fetching, updating, and deleting media assets
  - **Playback IDs**: Generate and manage playback IDs for media access

- ## Live API
  - **Create & Manage Live Streams**: Create, list, update, and delete live streams effortlessly
  - **Control Stream Access**: Generate playback IDs for live streams to control and manage access
  - **Simulcast to Multiple Platforms**: Stream content to multiple platforms simultaneously

For detailed usage, refer to the [FastPix API Reference](https://docs.fastpix.io/reference).

# Prerequisites:
- PHP 7.4 or later
- Composer package manager
- FastPix API credentials (Access Token and Secret Key)

## Getting started with FastPix:

<!-- Start Table of Contents [toc] -->
## Table of Contents
<!-- $toc-max-depth=2 -->
* [fastpix/sdk](#fastpixsdk)
  * [SDK Installation](#sdk-installation)
  * [Initialization](#initialization)
  * [SDK Example Usage](#sdk-example-usage)
  * [Available Resources and Operations](#available-resources-and-operations)
  * [Error Handling](#error-handling)
  * [Server Selection](#server-selection)
* [Development](#development)
  * [Maturity](#maturity)
  * [Detailed Usage](#detailed-usage)

<!-- End Table of Contents [toc] -->

<!-- Start SDK Installation [installation] -->
## SDK Installation

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

$sdk = Sdk\FastPix::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token-id',
            password: 'your-security-key',
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

$sdk = Sdk\FastPix::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token-id',
            password: 'your-security-key',
        )
    )
    ->build();

$request = new Components\CreateLiveStreamRequest(
    playbackSettings: new Components\PlaybackSettings(),
    inputMediaSettings: new Components\InputMediaSettings(
        metadata: new Components\CreateLiveStreamRequestMetadata(),
    ),
);

$response = $sdk->startLiveStream->createNewStream(
    request: $request
);

if ($response->liveStreamResponseDTO !== null) {
    // handle response
}
```
<!-- End SDK Example Usage [usage] -->

<!-- Start Available Resources and Operations [operations] -->
## Available Resources and Operations

<details open>
<summary>Available methods</summary>


### [inputVideo](docs/sdks/inputvideo/README.md)

* [createMedia](docs/sdks/inputvideo/README.md#createmedia) - Create media from URL
* [directUploadVideoMedia](docs/sdks/inputvideo/README.md#directuploadvideomedia) - Upload media from device

### [manageLiveStream](docs/sdks/managelivestream/README.md)

* [getAllStreams](docs/sdks/managelivestream/README.md#getallstreams) - Get all live streams
* [getLiveStreamById](docs/sdks/managelivestream/README.md#getlivestreambyid) - Get stream by ID
* [deleteLiveStream](docs/sdks/managelivestream/README.md#deletelivestream) - Delete a stream
* [updateLiveStream](docs/sdks/managelivestream/README.md#updatelivestream) - Update a stream

### [manageVideos](docs/sdks/managevideos/README.md)

* [listMedia](docs/sdks/managevideos/README.md#listmedia) - Get list of all media
* [getMedia](docs/sdks/managevideos/README.md#getmedia) - Get a media by ID
* [updatedMedia](docs/sdks/managevideos/README.md#updatedmedia) - Update a media by ID
* [deleteMedia](docs/sdks/managevideos/README.md#deletemedia) - Delete a media by ID
* [retrieveMediaInputInfo](docs/sdks/managevideos/README.md#retrievemediainputinfo) - Get info of media inputs

### [playback](docs/sdks/playback/README.md)

* [createPlaybackIdOfStream](docs/sdks/playback/README.md#createplaybackidofstream) - Create a playbackId
* [deletePlaybackIdOfStream](docs/sdks/playback/README.md#deleteplaybackidofstream) - Delete a playbackId
* [getLiveStreamPlaybackId](docs/sdks/playback/README.md#getlivestreamplaybackid) - Get stream's playbackId
* [createMediaPlaybackId](docs/sdks/playback/README.md#createmediaplaybackid) - Create a playback ID
* [deleteMediaPlaybackId](docs/sdks/playback/README.md#deletemediaplaybackid) - Delete a playback ID

### [simulcastStream](docs/sdks/simulcaststream/README.md)

* [createSimulcastOfStream](docs/sdks/simulcaststream/README.md#createsimulcastofstream) - Create a simulcast
* [deleteSimulcastOfStream](docs/sdks/simulcaststream/README.md#deletesimulcastofstream) - Delete a simulcast
* [getSpecificSimulcastOfStream](docs/sdks/simulcaststream/README.md#getspecificsimulcastofstream) - Get a specific simulcast of a stream
* [updateSpecificSimulcastOfStream](docs/sdks/simulcaststream/README.md#updatespecificsimulcastofstream) - Update a specific simulcast of a stream

### [startLiveStream](docs/sdks/startlivestream/README.md)

* [createNewStream](docs/sdks/startlivestream/README.md#createnewstream) - Create a new stream

</details>
<!-- End Available Resources and Operations [operations] -->

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

When custom error responses are specified for an operation, the SDK may also throw their associated exception. You can refer to respective *Errors* tables in SDK docs for more details on possible exception types for each operation. For example, the `createNewStream` method throws the following exceptions:

| Error Type                        | Status Code | Content Type     |
| --------------------------------- | ----------- | ---------------- |
| Errors\UnauthorizedException      | 401         | application/json |
| Errors\InvalidPermissionException | 403         | application/json |
| Errors\ValidationErrorResponse    | 422         | application/json |
| Errors\APIException               | 4XX, 5XX    | \*/\*            |

### Example

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Errors;

$sdk = Sdk\FastPix::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token-id',
            password: 'your-security-key',
        )
    )
    ->build();

try {
    $request = new Components\CreateLiveStreamRequest(
        playbackSettings: new Components\PlaybackSettings(),
        inputMediaSettings: new Components\InputMediaSettings(
            metadata: new Components\CreateLiveStreamRequestMetadata(),
        ),
    );

    $response = $sdk->startLiveStream->createNewStream(
        request: $request
    );

    if ($response->liveStreamResponseDTO !== null) {
        // handle response
    }
} catch (Errors\UnauthorizedExceptionThrowable $e) {
    // handle $e->$container data
    throw $e;
} catch (Errors\InvalidPermissionExceptionThrowable $e) {
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

$sdk = Sdk\FastPix::builder()
    ->setServerURL('https://v1.fastpix.io/live')
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token-id',
            password: 'your-security-key',
        )
    )
    ->build();

$request = new Components\CreateLiveStreamRequest(
    playbackSettings: new Components\PlaybackSettings(),
    inputMediaSettings: new Components\InputMediaSettings(
        metadata: new Components\CreateLiveStreamRequestMetadata(),
    ),
);

$response = $sdk->startLiveStream->createNewStream(
    request: $request
);

if ($response->liveStreamResponseDTO !== null) {
    // handle response
}
```
<!-- End Server Selection [server] -->

<!-- Placeholder for Future Speakeasy SDK Sections -->

# Development

## Maturity

This SDK is in beta, and there may be breaking changes between versions without a major version update. Therefore, we recommend pinning usage
to a specific package version. This way, you can install the same version each time without breaking changes unless you are intentionally
looking for the latest version.

## Detailed Usage

For a complete understanding of each API's functionality, including request and response details, parameter descriptions, and additional examples, please refer to the [FastPix API Reference](https://docs.fastpix.io/reference/signingkeys-overview).

The API reference provides comprehensive documentation for all available endpoints and features, ensuring developers can integrate and utilize FastPix APIs efficiently.

