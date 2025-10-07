# StartLiveStream
(*startLiveStream*)

## Overview

### Available Operations

* [createNewStream](#createnewstream) - Create a new stream

## createNewStream

Allows you to initiate a new <a href="https://docs.fastpix.io/docs/get-started-with-live-streaming">RTMPS</a> or <a href="https://docs.fastpix.io/docs/using-srt-to-live-stream">SRT</a> live stream on FastPix. Upon creating a stream, FastPix generates a unique `streamKey` and `srtSecret`, which can be used with any broadcasting software (like OBS) to connect to FastPix's RTMPS or SRT servers.
Leverage SRT for live streaming in environments with unstable networks, taking advantage of its error correction and encryption features for a resilient and secure broadcast. 

<h4>How it works</h4> 

1. Send a a `POST` request to this endpoint. You can configure the stream settings, including `metadata` (such as stream name and description), `reconnectWindow` (in case of disconnection), and privacy options (`public` or `private`). 

2. FastPix returns the stream details for both RTMPS and SRT configurations. These keys and IDs from the stream details are essential for connecting the broadcasting software to FastPix’s servers and transmitting the live stream to viewers.

3. Once the live stream is created, we’ll shoot a `POST` message to the address you give us with the webhook event <a href="https://docs.fastpix.io/docs/live-events#videolive_streamcreated">video.live_stream.created</a>.


**Example:**


  Imagine a gaming platform that allows users to live stream gameplay directly from their dashboard. The API creates a new stream, provides the necessary stream key, and sets it to "private" so that only specific viewers can access it. 


Related guide: <a href="https://docs.fastpix.io/docs/how-to-livestream">How to live stream</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="create-new-stream" method="post" path="/live/streams" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = FastPix\Sdk\SDK::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

$request = new Components\CreateLiveStreamRequest(
    playbackSettings: new Components\PlaybackSettings(
        accessPolicy: Components\BasicAccessPolicy::Public,
    ),
    inputMediaSettings: new Components\InputMediaSettings(
        mediaPolicy: Components\BasicAccessPolicy::Public,
        metadata: [
            'livestream_name' => 'fastpix_livestream',
        ],
        enableDvrMode: true,
    ),
);

$response = $sdk->startLiveStream->createNewStream(
    request: $request
);

if ($response->liveStreamResponseDTO !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                | Type                                                                                     | Required                                                                                 | Description                                                                              |
| ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- |
| `$request`                                                                               | [Components\CreateLiveStreamRequest](../../Models/Components/CreateLiveStreamRequest.md) | :heavy_check_mark:                                                                       | The request object to use for the request.                                               |

### Response

**[?Operations\CreateNewStreamResponse](../../Models/Operations/CreateNewStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |