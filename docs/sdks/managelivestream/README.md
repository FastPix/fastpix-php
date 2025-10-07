# ManageLiveStream
(*manageLiveStream*)

## Overview

### Available Operations

* [getAllStreams](#getallstreams) - Get all live streams
* [getLiveStreamViewerCountById](#getlivestreamviewercountbyid) - Get stream views by ID
* [getLiveStreamById](#getlivestreambyid) - Get stream by ID
* [deleteLiveStream](#deletelivestream) - Delete a stream
* [updateLiveStream](#updatelivestream) - Update a stream
* [enableLiveStream](#enablelivestream) - Enable a stream
* [disableLiveStream](#disablelivestream) - Disable a stream
* [completeLiveStream](#completelivestream) - Complete a stream

## getAllStreams

Retrieves a list of all live streams associated with the current workspace. It provides an overview of both current and past live streams, including details like `streamId`, `metadata`, `status`, `createdAt` and more.


#### How it works

Use the access token and secret key related to the workspace in the request header. When called, the API provides a paginated response containing all the live streams in that specific workspace. This is helpful for retrieving a large volume of streams and managing content in bulk.

### Example Usage

<!-- UsageSnippet language="php" operationID="get-all-streams" method="get" path="/live/streams" -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;

$sdk = FastPix\Sdk\SDK::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();



$response = $sdk->manageLiveStream->getAllStreams(
    limit: 20,
    offset: 1,
    orderBy: Operations\OrderBy::Desc

);

if ($response->getStreamsResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                           | Type                                                                                                                                | Required                                                                                                                            | Description                                                                                                                         | Example                                                                                                                             |
| ----------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- |
| `limit`                                                                                                                             | *?int*                                                                                                                              | :heavy_minus_sign:                                                                                                                  | Limit specifies the maximum number of items to display per page.                                                                    | 20                                                                                                                                  |
| `offset`                                                                                                                            | *?int*                                                                                                                              | :heavy_minus_sign:                                                                                                                  | Offset determines the starting point for data retrieval within a paginated list.                                                    | 1                                                                                                                                   |
| `orderBy`                                                                                                                           | [?Operations\OrderBy](../../Models/Operations/OrderBy.md)                                                                           | :heavy_minus_sign:                                                                                                                  | The list of value can be order in two ways DESC (Descending) or ASC (Ascending). In case not specified, by default it will be DESC. | desc                                                                                                                                |

### Response

**[?Operations\GetAllStreamsResponse](../../Models/Operations/GetAllStreamsResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## getLiveStreamViewerCountById

This endpoint retrieves the current number of viewers watching a specific live stream, identified by its unique `streamId`.

The viewer count is an **approximate value**, optimized for performance. It provides a near-real-time estimate of how many clients are actively watching the stream. This approach ensures high efficiency, especially when the stream is being watched at large scale across multiple devices or platforms.

#### Example

Suppose a content creator is hosting a live concert and wants to display the number of live viewers on their dashboard. This endpoint can be queried to show up-to-date viewer statistics.

Related guide: <a href="https://docs.fastpix.io/docs/manage-streams">Manage streams</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="get-live-stream-viewer-count-by-id" method="get" path="/live/streams/{streamId}/viewer-count" -->
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



$response = $sdk->manageLiveStream->getLiveStreamViewerCountById(
    streamId: '61a264dcc447b63da6fb79ef925cd76d'
);

if ($response->viewsCountResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                           | Type                                                                                | Required                                                                            | Description                                                                         | Example                                                                             |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `streamId`                                                                          | *string*                                                                            | :heavy_check_mark:                                                                  | Upon creating a new live stream, FastPix assigns a unique identifier to the stream. | 61a264dcc447b63da6fb79ef925cd76d                                                    |

### Response

**[?Operations\GetLiveStreamViewerCountByIdResponse](../../Models/Operations/GetLiveStreamViewerCountByIdResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\LiveNotFoundError          | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## getLiveStreamById

This endpoint retrieves details about a specific live stream by its unique `streamId`. It includes data such as the stream’s `status` (idle, preparing, active, disabled), `metadata` (title, description), and more. 
#### Example

  Suppose a news agency is broadcasting a live event and wants to track the configurations set for the live stream while also checking the stream's status.


Related guide: <a href="https://docs.fastpix.io/docs/manage-streams">Manage streams</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="get-live-stream-by-id" method="get" path="/live/streams/{streamId}" -->
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

  By providing the `streamId`, the API will terminate any active connections to the stream and remove it from the list of available live streams. You can further look for <a href="https://docs.fastpix.io/docs/live-events#videolive_streamdeleted">video.live_stream.deleted</a> webhook to notify your system about the status. 

  #### Example

  For an online concert platform, a trial stream was mistakenly made public. The event manager deletes the stream before the concert begins to avoid confusion among viewers. 


  Related guide: <a href="https://docs.fastpix.io/docs/manage-streams">Manage streams</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="delete-live-stream" method="delete" path="/live/streams/{streamId}" -->
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
| Errors\LiveNotFoundError          | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## updateLiveStream

This endpoint allows you to modify the parameters of an existing live stream, such as its `metadata` (title, description) or the `reconnectWindow`. It’s useful for making changes to a stream that has already been created but not yet ended. Once the live stream is disabled, you cannot update a stream. 


  The updated stream parameters and the `streamId` needs to be shared in the request, and FastPix will return the updated stream details. Once updated, <a href="https://docs.fastpix.io/docs/live-events#videolive_streamupdated">video.live_stream.updated</a> webhook event notifies your system. 

 #### Example

 A host realizes they need to extend the reconnect window for their live stream in case they lose connection temporarily during the event. Or suppose during a multi-day online conference, the event organizers need to update the stream title to reflect the next day's session while keeping the same stream ID for continuity. 



  Related guide: <a href="https://docs.fastpix.io/docs/manage-streams">Manage streams</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="update-live-stream" method="patch" path="/live/streams/{streamId}" -->
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

$patchLiveStreamRequest = new Components\PatchLiveStreamRequest(
    metadata: [
        'livestream_name' => 'Gaming_stream',
    ],
    reconnectWindow: 100,
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

| Parameter                                                                              | Type                                                                                   | Required                                                                               | Description                                                                            | Example                                                                                |
| -------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------- |
| `streamId`                                                                             | *string*                                                                               | :heavy_check_mark:                                                                     | Upon creating a new live stream, FastPix assigns a unique identifier to the stream.    | 91a264dcc447b63da6fb79ef925cd76d                                                       |
| `patchLiveStreamRequest`                                                               | [Components\PatchLiveStreamRequest](../../Models/Components/PatchLiveStreamRequest.md) | :heavy_check_mark:                                                                     | N/A                                                                                    | {<br/>"metadata": {<br/>"livestream_name": "Gaming_stream"<br/>},<br/>"reconnectWindow": 100<br/>} |

### Response

**[?Operations\UpdateLiveStreamResponse](../../Models/Operations/UpdateLiveStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\LiveNotFoundError          | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## enableLiveStream

This endpoint allows you to enable a livestream by transitioning its status from `disabled` to `idle`. Once enabled, the stream becomes available and ready to accept an incoming broadcast from a streaming tool.

Streams on the trial plan cannot be re-enabled if they are in the `disabled` state.

The `livestreamId` must be provided in the path, and the stream must not already be in an enabled state (`idle`, `preparing`, or `active`).

#### Example

A creator disables a livestream to pause it temporarily. Later, they decide to continue the session. By calling this endpoint with the stream's ID, they can re-enable and restart the same livestream.

Related guide <a href="https://docs.fastpix.io/docs/manage-streams">Manage streams</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="enable-live-stream" method="put" path="/live/streams/{streamId}/live-enable" -->
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



$response = $sdk->manageLiveStream->enableLiveStream(
    streamId: '91a264dcc447b63da6fb79ef925cd76d'
);

if ($response->liveStreamDeleteResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                           | Type                                                                                | Required                                                                            | Description                                                                         | Example                                                                             |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `streamId`                                                                          | *string*                                                                            | :heavy_check_mark:                                                                  | Upon creating a new live stream, FastPix assigns a unique identifier to the stream. | 91a264dcc447b63da6fb79ef925cd76d                                                    |

### Response

**[?Operations\EnableLiveStreamResponse](../../Models/Operations/EnableLiveStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\TrialPlanRestrictionError  | 400                               | application/json                  |
| Errors\StreamAlreadyEnabledError  | 400                               | application/json                  |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundError              | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## disableLiveStream

This endpoint disables a livestream by setting its status to `disabled`. Use this to stop a livestream when it's no longer needed or should be taken offline intentionally.

A disabled stream can later be re-enabled using the enable endpoint — however, if you're on a trial plan, re-enabling is not allowed once the stream is disabled.

#### Example

A speaker finishes their live session and wants to prevent the stream from being mistakenly started again. By calling this endpoint, the stream is transitioned to a `disabled` state, ensuring it's permanently stopped (unless re-enabled on a paid plan).

Related guide <a href="https://docs.fastpix.io/docs/manage-streams">Manage streams</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="disable-live-stream" method="put" path="/live/streams/{streamId}/live-disable" -->
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



$response = $sdk->manageLiveStream->disableLiveStream(
    streamId: '91a264dcc447b63da6fb79ef925cd76d'
);

if ($response->liveStreamDeleteResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                           | Type                                                                                | Required                                                                            | Description                                                                         | Example                                                                             |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `streamId`                                                                          | *string*                                                                            | :heavy_check_mark:                                                                  | Upon creating a new live stream, FastPix assigns a unique identifier to the stream. | 91a264dcc447b63da6fb79ef925cd76d                                                    |

### Response

**[?Operations\DisableLiveStreamResponse](../../Models/Operations/DisableLiveStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\StreamAlreadyDisabledError | 400                               | application/json                  |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\LiveNotFoundError          | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## completeLiveStream

This endpoint marks a livestream as completed by stopping the active stream and transitioning its status to `idle`. It is typically used after a livestream session has ended.

This operation only works when the stream is in the `active` state.

Completing a stream can help finalize the session and trigger post-processing events like VOD generation.

#### Example

A virtual event ends, and the system or host needs to close the livestream to prevent further streaming. This endpoint ensures the livestream status is changed from `active` to `idle`, indicating it's officially completed.

Related guide <a href="https://docs.fastpix.io/docs/manage-streams">Manage streams</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="complete-live-stream" method="put" path="/live/streams/{streamId}/finish" -->
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



$response = $sdk->manageLiveStream->completeLiveStream(
    streamId: '91a264dcc447b63da6fb79ef925cd76d'
);

if ($response->liveStreamDeleteResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                           | Type                                                                                | Required                                                                            | Description                                                                         | Example                                                                             |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `streamId`                                                                          | *string*                                                                            | :heavy_check_mark:                                                                  | Upon creating a new live stream, FastPix assigns a unique identifier to the stream. | 91a264dcc447b63da6fb79ef925cd76d                                                    |

### Response

**[?Operations\CompleteLiveStreamResponse](../../Models/Operations/CompleteLiveStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 400, 401                          | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundError              | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |