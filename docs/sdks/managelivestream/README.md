# ManageLiveStream
(*manageLiveStream*)

## Overview

### Available Operations

* [getAllStreams](#getallstreams) - Get all live streams
* [getLiveStreamById](#getlivestreambyid) - Get stream by ID
* [deleteLiveStream](#deletelivestream) - Delete a stream
* [updateLiveStream](#updatelivestream) - Update a stream

## getAllStreams

Retrieves a list of all live streams associated with the user’s account (workspace). It provides an overview of both current and past live streams, including details like streamId, title, status, and creation time. 

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



$response = $sdk->manageLiveStream->getAllStreams(
    limit: '20',
    offset: '1',
    orderBy: Operations\GetAllStreamsOrderBy::Desc

);

if ($response->getStreamsResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                           | Type                                                                                                                                | Required                                                                                                                            | Description                                                                                                                         | Example                                                                                                                             |
| ----------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- |
| `limit`                                                                                                                             | *?string*                                                                                                                           | :heavy_minus_sign:                                                                                                                  | Limit specifies the maximum number of items to display per page.                                                                    | 20                                                                                                                                  |
| `offset`                                                                                                                            | *?string*                                                                                                                           | :heavy_minus_sign:                                                                                                                  | Offset determines the starting point for data retrieval within a paginated list.                                                    | 1                                                                                                                                   |
| `orderBy`                                                                                                                           | [?Operations\GetAllStreamsOrderBy](../../Models/Operations/GetAllStreamsOrderBy.md)                                                 | :heavy_minus_sign:                                                                                                                  | The list of value can be order in two ways DESC (Descending) or ASC (Ascending). In case not specified, by default it will be DESC. | desc                                                                                                                                |

### Response

**[?Operations\GetAllStreamsResponse](../../Models/Operations/GetAllStreamsResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## getLiveStreamById

This endpoint retrieves detailed information about a specific live stream by its unique streamId. It includes data such as the stream’s status (idle, preparing, active, disabled), metadata (title, description), and more. 

  **Practical example:** Suppose a news agency is broadcasting a live event and wants to track the configurations set for the live stream while also checking the stream's status.

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



$response = $sdk->manageLiveStream->getLiveStreamById(
    streamId: '61a264dcc447b63da6fb79ef925cd76d'
);

if ($response->livestreamgetResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                           | Type                                                                                | Required                                                                            | Description                                                                         | Example                                                                             |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `streamId`                                                                          | *string*                                                                            | :heavy_check_mark:                                                                  | Upon creating a new live stream, FastPix assigns a unique identifier to the stream. | 61a264dcc447b63da6fb79ef925cd76d                                                    |

### Response

**[?Operations\GetLiveStreamByIdResponse](../../Models/Operations/GetLiveStreamByIdResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundError              | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## deleteLiveStream

Permanently removes a specified live stream from the workspace. If the stream is still active, the encoder will be disconnected, and the ingestion will stop. This action cannot be undone, and any future playback attempts will fail. 

  By providing the streamId, the API will terminate any active connections to the stream and remove it from the list of available live streams. You can further look for video.live_stream.deleted webhook to notify your system about the status. 

  **Example:** For an online concert platform, a trial stream was mistakenly made public. The event manager deletes the stream before the concert begins to avoid confusion among viewers. 

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



$response = $sdk->manageLiveStream->deleteLiveStream(
    streamId: '8717422d89288ad5958d4a86e9afe2a2'
);

if ($response->liveStreamDeleteResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                           | Type                                                                                | Required                                                                            | Description                                                                         | Example                                                                             |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `streamId`                                                                          | *string*                                                                            | :heavy_check_mark:                                                                  | Upon creating a new live stream, FastPix assigns a unique identifier to the stream. | 8717422d89288ad5958d4a86e9afe2a2                                                    |

### Response

**[?Operations\DeleteLiveStreamResponse](../../Models/Operations/DeleteLiveStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundError              | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## updateLiveStream

This endpoint allows users to modify the parameters of an existing live stream, such as its metadata (title, description) or the reconnect window. It’s useful for making changes to a stream that has already been created but not yet ended. Once the live stream is disabled, you cannot update a stream. 


  The updated stream parameters and the streamId needs to be shared in the request, and FastPix will return the updated stream details. Once updated, video.live_stream.updated webhook event notifies your system. 

  **Practical example:** A host realizes they need to extend the reconnect window for their live stream in case they lose connection temporarily during the event. Or suppose during a multi-day online conference, the event organizers need to update the stream title to reflect the next day's session while keeping the same stream ID for continuity. 

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

$patchLiveStreamRequest = new Components\PatchLiveStreamRequest(
    metadata: new Components\PatchLiveStreamRequestMetadata(),
);

$response = $sdk->manageLiveStream->updateLiveStream(
    streamId: '91a264dcc447b63da6fb79ef925cd76d',
    patchLiveStreamRequest: $patchLiveStreamRequest

);

if ($response->patchResponseDTO !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                               | Type                                                                                    | Required                                                                                | Description                                                                             | Example                                                                                 |
| --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- |
| `streamId`                                                                              | *string*                                                                                | :heavy_check_mark:                                                                      | Upon creating a new live stream, FastPix assigns a unique identifier to the stream.     | 91a264dcc447b63da6fb79ef925cd76d                                                        |
| `patchLiveStreamRequest`                                                                | [?Components\PatchLiveStreamRequest](../../Models/Components/PatchLiveStreamRequest.md) | :heavy_minus_sign:                                                                      | N/A                                                                                     | {<br/>"metadata": {<br/>"livestream_name": "Gaming_stream"<br/>},<br/>"reconnectWindow": 100<br/>} |

### Response

**[?Operations\UpdateLiveStreamResponse](../../Models/Operations/UpdateLiveStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundError              | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |