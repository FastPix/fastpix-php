# SimulcastStream
(*simulcastStream*)

## Overview

### Available Operations

* [createSimulcastOfStream](#createsimulcastofstream) - Create a simulcast
* [deleteSimulcastOfStream](#deletesimulcastofstream) - Delete a simulcast
* [getSpecificSimulcastOfStream](#getspecificsimulcastofstream) - Get a specific simulcast
* [updateSpecificSimulcastOfStream](#updatespecificsimulcastofstream) - Update a simulcast

## createSimulcastOfStream

Lets you to create a simulcast for a parent live stream. Simulcasting enables you to broadcast the live stream to multiple social platforms simultaneously (e.g., YouTube, Facebook, or Twitch). This feature is useful for expanding your audience reach across different platforms. However, a simulcast can only be created when the parent live stream is in idle state (i.e., not currently live or disabled). Additionally, only one simulcast target can be created per API call. 
#### How it works

1. Upon calling this endpoint, you need to provide the parent `streamId` and the details of the simulcast target (platform and credentials). The system will generate a unique `simulcastId`, which can be used to manage the simulcast later. 

2. To notify your application about the status of simulcast related events check for the <a href="https://docs.fastpix.io/docs/webhooks-collection#simulcast-target-events">webhooks for simulcast</a> target events. 

#### Example
An event manager sets up a live stream for a virtual conference and wants to simulcast the stream on YouTube and Facebook Live. They first create the primary live stream in FastPix, ensuring it's in the idle state. Then, they use the API to create a simulcast target for YouTube. 

Related guide: <a href="https://docs.fastpix.io/docs/simulcast-to-3rd-party-platforms">Simulcast to 3rd party platforms</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="create-simulcast-of-stream" method="post" path="/live/streams/{streamId}/simulcast" -->
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

$simulcastRequest = new Components\SimulcastRequest(
    url: 'rtmp://hyd01.contribute.live-video.net/app/',
    streamKey: 'live_1012464221_DuM8W004MoZYNxQEZ0czODgfHCFBhk',
    metadata: [
        'livestream_name' => 'Tech-Connect Summit',
    ],
);

$response = $sdk->simulcastStream->createSimulcastOfStream(
    streamId: '8717422d89288ad5958d4a86e9afe2a2',
    simulcastRequest: $simulcastRequest

);

if ($response->simulcastResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                         | Type                                                                                                                                                                              | Required                                                                                                                                                                          | Description                                                                                                                                                                       | Example                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `streamId`                                                                                                                                                                        | *string*                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                | Upon creating a new live stream, FastPix assigns a unique identifier to the stream.                                                                                               | 8717422d89288ad5958d4a86e9afe2a2                                                                                                                                                  |
| `simulcastRequest`                                                                                                                                                                | [Components\SimulcastRequest](../../Models/Components/SimulcastRequest.md)                                                                                                        | :heavy_check_mark:                                                                                                                                                                | N/A                                                                                                                                                                               | {<br/>"url": "rtmp://hyd01.contribute.live-video.net/app/",<br/>"streamKey": "live_1012464221_DuM8W004MoZYNxQEZ0czODgfHCFBhk",<br/>"metadata": {<br/>"livestream_name": "Tech-Connect Summit"<br/>}<br/>} |

### Response

**[?Operations\CreateSimulcastOfStreamResponse](../../Models/Operations/CreateSimulcastOfStreamResponse.md)**

### Errors

| Error Type                           | Status Code                          | Content Type                         |
| ------------------------------------ | ------------------------------------ | ------------------------------------ |
| Errors\SimulcastUnavailableException | 400                                  | application/json                     |
| Errors\UnauthorizedException         | 401                                  | application/json                     |
| Errors\InvalidPermissionException    | 403                                  | application/json                     |
| Errors\LiveNotFoundError             | 404                                  | application/json                     |
| Errors\ValidationErrorResponse       | 422                                  | application/json                     |
| Errors\APIException                  | 4XX, 5XX                             | \*/\*                                |

## deleteSimulcastOfStream

Allows you to delete a simulcast using its unique `simulcastId`, which was returned during the simulcast creation process. Deleting a simulcast stops the broadcast to the associated platform, but the parent stream will continue to run if it is live. This action is irreversible, and a new simulcast would need to be created if you want to resume streaming to the same platform. 

Webhook event: <a href="https://docs.fastpix.io/docs/live-events#videolive_streamsimulcast_targetdeleted">video.live_stream.simulcast_target.deleted</a>


#### Example
A broadcaster needs to stop simulcasting to one platform due to technical difficulties while keeping the stream active on others. For instance, a tech company is simulcasting a product launch on multiple platforms. Midway through the event, they decide to stop the simulcast on Facebook due to performance issues, but keep it running on YouTube. They call this API to delete the Facebook simulcast target.

### Example Usage

<!-- UsageSnippet language="php" operationID="delete-simulcast-of-stream" method="delete" path="/live/streams/{streamId}/simulcast/{simulcastId}" -->
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



$response = $sdk->simulcastStream->deleteSimulcastOfStream(
    streamId: '8717422d89288ad5958d4a86e9afe2a2',
    simulcastId: '9217422d89288ad5958d4a86e9afe2a1'

);

if ($response->simulcastdeleteResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    | Example                                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `streamId`                                                                                                                     | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | Upon creating a new live stream, FastPix assigns a unique identifier to the stream.                                            | 8717422d89288ad5958d4a86e9afe2a2                                                                                               |
| `simulcastId`                                                                                                                  | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | When you create the new simulcast, FastPix assign a universal unique identifier which can contain a maximum of 255 characters. | 9217422d89288ad5958d4a86e9afe2a1                                                                                               |

### Response

**[?Operations\DeleteSimulcastOfStreamResponse](../../Models/Operations/DeleteSimulcastOfStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundErrorSimulcast     | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## getSpecificSimulcastOfStream

Retrieves the details of a specific simulcast associated with a parent live stream. By providing both the `streamId` of the parent stream and the `simulcastId`, FastPix returns detailed information about the simulcast, such as the stream URL, the status of the simulcast, and metadata. 

#### Example
This endpoint can be used to verify the status of the simulcast on external platforms before the live stream begins. For instance, before starting a live gaming event, the organizer wants to ensure that the simulcast to Twitch is set up correctly. They retrieve the simulcast information to confirm that everything is properly configured.

### Example Usage

<!-- UsageSnippet language="php" operationID="get-specific-simulcast-of-stream" method="get" path="/live/streams/{streamId}/simulcast/{simulcastId}" -->
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



$response = $sdk->simulcastStream->getSpecificSimulcastOfStream(
    streamId: '8717422d89288ad5958d4a86e9afe2a2',
    simulcastId: '8717422d89288ad5958d4a86e9afe2a2'

);

if ($response->simulcastResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    | Example                                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `streamId`                                                                                                                     | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | Upon creating a new live stream, FastPix assigns a unique identifier to the stream.                                            | 8717422d89288ad5958d4a86e9afe2a2                                                                                               |
| `simulcastId`                                                                                                                  | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | When you create the new simulcast, FastPix assign a universal unique identifier which can contain a maximum of 255 characters. | 8717422d89288ad5958d4a86e9afe2a2                                                                                               |

### Response

**[?Operations\GetSpecificSimulcastOfStreamResponse](../../Models/Operations/GetSpecificSimulcastOfStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundErrorSimulcast     | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## updateSpecificSimulcastOfStream

Allows you to enable or disable a specific simulcast associated with a parent live stream. The status of the simulcast can be updated at any point, whether the live stream is active or idle. However, once the live stream is disabled, the simulcast can no longer be modified. 

Webhook event: <a href="https://docs.fastpix.io/docs/live-events#videolive_streamsimulcast_targetupdated">video.live_stream.simulcast_target.updated</a>

#### Example
When a `PATCH` request is made to this endpoint, the API updates the status of the simulcast. This can be useful for pausing or resuming a simulcast on a particular platform without stopping the parent live stream.

### Example Usage

<!-- UsageSnippet language="php" operationID="update-specific-simulcast-of-stream" method="put" path="/live/streams/{streamId}/simulcast/{simulcastId}" -->
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

$simulcastUpdateRequest = new Components\SimulcastUpdateRequest(
    isEnabled: false,
    metadata: [
        'simulcast_name' => 'Tech today',
    ],
);

$response = $sdk->simulcastStream->updateSpecificSimulcastOfStream(
    streamId: '9714422d89287ad5758d4a86e9afe1a2',
    simulcastId: '8717422d89288ad5958d4a86e9afe2a2',
    simulcastUpdateRequest: $simulcastUpdateRequest

);

if ($response->simulcastUpdateResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    | Example                                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `streamId`                                                                                                                     | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | Upon creating a new live stream, FastPix assigns a unique identifier to the stream.                                            | 9714422d89287ad5758d4a86e9afe1a2                                                                                               |
| `simulcastId`                                                                                                                  | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | When you create the new simulcast, FastPix assign a universal unique identifier which can contain a maximum of 255 characters. | 8717422d89288ad5958d4a86e9afe2a2                                                                                               |
| `simulcastUpdateRequest`                                                                                                       | [Components\SimulcastUpdateRequest](../../Models/Components/SimulcastUpdateRequest.md)                                         | :heavy_check_mark:                                                                                                             | N/A                                                                                                                            | {<br/>"isEnabled": false,<br/>"metadata": {<br/>"simulcast_name": "Tech today"<br/>}<br/>}                                     |

### Response

**[?Operations\UpdateSpecificSimulcastOfStreamResponse](../../Models/Operations/UpdateSpecificSimulcastOfStreamResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\UnauthorizedException      | 401                               | application/json                  |
| Errors\InvalidPermissionException | 403                               | application/json                  |
| Errors\NotFoundErrorSimulcast     | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |