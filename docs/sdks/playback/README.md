# Playback
(*playback*)

## Overview

### Available Operations

* [createPlaybackIdOfStream](#createplaybackidofstream) - Create a playbackId
* [deletePlaybackIdOfStream](#deleteplaybackidofstream) - Delete a playbackId
* [getLiveStreamPlaybackId](#getlivestreamplaybackid) - Get stream's playbackId
* [createMediaPlaybackId](#createmediaplaybackid) - Create a playback ID
* [deleteMediaPlaybackId](#deletemediaplaybackid) - Delete a playback ID

## createPlaybackIdOfStream

Generates a new playback ID for the live stream, allowing viewers to access the stream through this ID. The playback ID can be shared with viewers for direct access to the live broadcast. 

  By calling this endpoint with the streamId, FastPix returns a unique playbackId, which can be used to stream the live content. 

  **Use case:** A media platform needs to distribute a unique playback ID to users for an exclusive live concert. The platform can also embed the stream on various partner websites. 

### Example Usage

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\FastPix::builder()
    ->setSecurity(
        new Components\Security(
            username: '',
            password: '',
        )
    )
    ->build();

$playbackIdRequest = new Components\PlaybackIdRequest();

$response = $sdk->playback->createPlaybackIdOfStream(
    streamId: '8717422d89288ad5958d4a86e9afe2a2',
    playbackIdRequest: $playbackIdRequest

);

if ($response->playbackIdResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                           | Type                                                                                | Required                                                                            | Description                                                                         | Example                                                                             |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `streamId`                                                                          | *string*                                                                            | :heavy_check_mark:                                                                  | Upon creating a new live stream, FastPix assigns a unique identifier to the stream. | 8717422d89288ad5958d4a86e9afe2a2                                                    |
| `playbackIdRequest`                                                                 | [?Components\PlaybackIdRequest](../../Models/Components/PlaybackIdRequest.md)       | :heavy_minus_sign:                                                                  | N/A                                                                                 | {<br/>"accessPolicy": "public"<br/>}                                                |

### Response

**[?Operations\CreatePlaybackIdOfStreamResponse](../../Models/Operations/CreatePlaybackIdOfStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundError              | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## deletePlaybackIdOfStream

Deletes a previously created playback ID for a live stream. This will prevent any new viewers from accessing the stream through the playback ID, though current viewers will be able to continue watching for a limited time before being disconnected. By providing the playbackId, FastPix deletes the ID and ensures new playback requests will fail. 

  **Use case:** A streaming service wants to prevent new users from joining a live stream that is nearing its end. The host can delete the playback ID to ensure no one can join the stream or replay it once it ends. 

### Example Usage

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\FastPix::builder()
    ->setSecurity(
        new Components\Security(
            username: '',
            password: '',
        )
    )
    ->build();



$response = $sdk->playback->deletePlaybackIdOfStream(
    streamId: '8717422d89288ad5958d4a86e9afe2a2',
    playbackId: '88b7ac0f-2504-4dd5-b7b4-d84ab4fee1bd'

);

if ($response->liveStreamDeleteResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                           | Type                                                                                | Required                                                                            | Description                                                                         | Example                                                                             |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `streamId`                                                                          | *string*                                                                            | :heavy_check_mark:                                                                  | Upon creating a new live stream, FastPix assigns a unique identifier to the stream. | 8717422d89288ad5958d4a86e9afe2a2                                                    |
| `playbackId`                                                                        | *string*                                                                            | :heavy_check_mark:                                                                  | Unique identifier for the playbackId                                                | 88b7ac0f-2504-4dd5-b7b4-d84ab4fee1bd                                                |

### Response

**[?Operations\DeletePlaybackIdOfStreamResponse](../../Models/Operations/DeletePlaybackIdOfStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundErrorPlaybackId    | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## getLiveStreamPlaybackId

Retrieves details about a previously created playback ID. If you provide the distinct playback ID that was given back to you in the previous stream or playbackId create request, FastPix will provide the relevant playback details such as the access policy. 

  **Use case:** A developer needs to confirm the playback ID details to ensure the right stream is being accessed by viewers. 

### Example Usage

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\FastPix::builder()
    ->setSecurity(
        new Components\Security(
            username: '',
            password: '',
        )
    )
    ->build();



$response = $sdk->playback->getLiveStreamPlaybackId(
    streamId: '61a264dcc447b63da6fb79ef925cd76d',
    playbackId: '61a264dcc447b63da6fb79ef925cd76d'

);

if ($response->playbackIdResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                            | Type                                                                                 | Required                                                                             | Description                                                                          | Example                                                                              |
| ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ |
| `streamId`                                                                           | *string*                                                                             | :heavy_check_mark:                                                                   | Upon creating a new live stream, FastPix assigns a unique identifier to the stream.  | 61a264dcc447b63da6fb79ef925cd76d                                                     |
| `playbackId`                                                                         | *string*                                                                             | :heavy_check_mark:                                                                   | Upon creating a new playbackId, FastPix assigns a unique identifier to the playback. | 61a264dcc447b63da6fb79ef925cd76d                                                     |

### Response

**[?Operations\GetLiveStreamPlaybackIdResponse](../../Models/Operations/GetLiveStreamPlaybackIdResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundErrorPlaybackId    | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## createMediaPlaybackId

You can create a new playback ID for a specific media asset. If you have already retrieved an existing playbackId using the "Get Media by ID" endpoint for a media asset, you can use this endpoint to generate a new playback ID with a specified access policy. 



If you want to create a private playback ID for a media asset that already has a public playback ID, this endpoint also allows you to do so by specifying the desired access policy. 

#### How it works

1. **Make a POST request** to the **/on-demand/`<mediaId>`/playback-ids** endpoint, replacing `<mediaId>` with the uploadId or id of the media asset. 

2. Include the **access policy** in the request body to indicate whether the new playback ID should be private or public. 

3. Receive a response containing the newly created playback ID with the requested access level. 


**Use case:** A video streaming service generates playback IDs for each media file when users request to view specific content. The playback ID is then used by the video player to stream the video.


### Example Usage

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;

$sdk = Sdk\FastPix::builder()
    ->setSecurity(
        new Components\Security(
            username: '',
            password: '',
        )
    )
    ->build();

$requestBody = new Operations\CreateMediaPlaybackIdRequestBody(
    accessPolicy: Operations\CreateMediaPlaybackIdAccessPolicy::Public,
);

$response = $sdk->playback->createMediaPlaybackId(
    mediaId: 'dbb8a39a-e4a5-4120-9f22-22f603f1446e',
    requestBody: $requestBody

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                         | Type                                                                                                              | Required                                                                                                          | Description                                                                                                       | Example                                                                                                           |
| ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- |
| `mediaId`                                                                                                         | *string*                                                                                                          | :heavy_check_mark:                                                                                                | When creating the media, FastPix assigns a universally unique identifier with a maximum length of 255 characters. | dbb8a39a-e4a5-4120-9f22-22f603f1446e                                                                              |
| `requestBody`                                                                                                     | [?Operations\CreateMediaPlaybackIdRequestBody](../../Models/Operations/CreateMediaPlaybackIdRequestBody.md)       | :heavy_minus_sign:                                                                                                | Request body for creating playback id for an media                                                                | {<br/>"accessPolicy": "public"<br/>}                                                                              |

### Response

**[?Operations\CreateMediaPlaybackIdResponse](../../Models/Operations/CreateMediaPlaybackIdResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ForbiddenException         | 403                               | application/json                  |
| Errors\MediaNotFoundException     | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## deleteMediaPlaybackId

This endpoint allows you to remove a specific playback ID associated with a media asset. Deleting a playbackId will revoke access to the media content linked to that ID. 


#### How it works

1. Make a DELETE request to the **/on-demand/`<mediaId>`/playback-ids** endpoint, replacing `<mediaId>` with the uploadId or id of the media asset from which you want to delete the playback ID. 

2. Specify the playback ID you wish to delete in the request body. 

**Use case:** Your platform offers limited-time access to premium content. When the subscription expires, you can revoke access to the content by deleting the associated playback ID, preventing users from streaming the video further. 


### Example Usage

```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\FastPix::builder()
    ->setSecurity(
        new Components\Security(
            username: '',
            password: '',
        )
    )
    ->build();



$response = $sdk->playback->deleteMediaPlaybackId(
    mediaId: 'dbb8a39a-e4a5-4120-9f22-22f603f1446e',
    playbackId: 'dbb8a39a-e4a5-4120-9f22-22f603f1446e'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                             | Type                                                                                                  | Required                                                                                              | Description                                                                                           | Example                                                                                               |
| ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| `mediaId`                                                                                             | *string*                                                                                              | :heavy_check_mark:                                                                                    | Return the universal unique identifier for media which can contain a maximum of 255 characters.       | dbb8a39a-e4a5-4120-9f22-22f603f1446e                                                                  |
| `playbackId`                                                                                          | *string*                                                                                              | :heavy_check_mark:                                                                                    | Return the universal unique identifier for playbacks  which can contain a maximum of 255 characters.  | dbb8a39a-e4a5-4120-9f22-22f603f1446e                                                                  |

### Response

**[?Operations\DeleteMediaPlaybackIdResponse](../../Models/Operations/DeleteMediaPlaybackIdResponse.md)**

### Errors

| Error Type                              | Status Code                             | Content Type                            |
| --------------------------------------- | --------------------------------------- | --------------------------------------- |
| Errors\InvalidPermissionException       | 401                                     | application/json                        |
| Errors\ForbiddenException               | 403                                     | application/json                        |
| Errors\MediaOrPlaybackNotFoundException | 404                                     | application/json                        |
| Errors\ValidationErrorResponse          | 422                                     | application/json                        |
| Errors\APIException                     | 4XX, 5XX                                | \*/\*                                   |