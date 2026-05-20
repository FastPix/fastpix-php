# SimulcastStream

## Overview

Operations for simulcast stream management

### Available Operations

* [createSimulcastOfStream](#createsimulcastofstream) - Create a simulcast
* [deleteSimulcastOfStream](#deletesimulcastofstream) - Delete a simulcast
* [getSpecificSimulcastOfStream](#getspecificsimulcastofstream) - Get a specific simulcast
* [updateSpecificSimulcastOfStream](#updatespecificsimulcastofstream) - Update a simulcast

## createSimulcastOfStream

Creates a simulcast for a parent live stream. Simulcasting allows you to broadcast a live stream to multiple social platforms simultaneously (for example, YouTube, Facebook, or Twitch). This helps expand your audience reach across platforms. A simulcast can only be created when the parent live stream is in the idle state (not currently live or disabled). Only one simulcast target can be created per API call. 
#### How it works

1. Change to: When you call this endpoint, provide the parent `streamId` along with the simulcast target details (such as platform and credentials). The API returns a unique `simulcastId`, which you can use to manage the simulcast later.  

2. To notify your application about the status of simulcast related events check for the <a href="https://fastpix.com/docs/webhooks/webhook-event-reference#simulcast-target-events">webhooks for simulcast</a> target events. 

#### Example
An event manager sets up a live stream for a virtual conference and wants to simulcast the stream on YouTube and Facebook Live. They first create the primary live stream in FastPix, ensuring it's in the idle state. Then, they use the API to create a simulcast target for YouTube. 

Related guide: <a href="https://fastpix.com/docs/edit-and-transform-live-stream/simulcast-to-multiple-platforms">Simulcast to 3rd party platforms</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="create-simulcast-of-stream" method="post" path="/live/streams/{streamId}/simulcast" -->
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

    // The ID of the stream to create a simulcast for
    $streamId = 'your-stream-id';

    $body = new Components\SimulcastRequest(
        url: 'rtmp://hyd01.contribute.live-video.net/app/',
        streamKey: 'live_1012464221_DuM8W004MoZYNxQEZ0czODgfHCFBhk',
        metadata: [
            'livestream_name' => 'Tech-Connect Summit',
        ]);

    $response = $sdk->simulcastStream->createSimulcastOfStream(
        body: $body,
        streamId: $streamId,
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

### Parameters

| Parameter                                                                                                                                                                         | Type                                                                                                                                                                              | Required                                                                                                                                                                          | Description                                                                                                                                                                       | Example                                                                                                                                                                           |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `streamId`                                                                                                                                                                        | *string*                                                                                                                                                                          | :heavy_check_mark:                                                                                                                                                                | After creating a new live stream, FastPix assigns a unique identifier to the stream.                                                                                              | 8717422d89288ad5958d4a86e9afe2a2                                                                                                                                                  |
| `body`                                                                                                                                                                            | [Components\SimulcastRequest](../../Models/Components/SimulcastRequest.md)                                                                                                        | :heavy_check_mark:                                                                                                                                                                | N/A                                                                                                                                                                               | {<br/>"url": "rtmp://hyd01.contribute.live-video.net/app/",<br/>"streamKey": "live_1012464221_DuM8W004MoZYNxQEZ0czODgfHCFBhk",<br/>"metadata": {<br/>"livestream_name": "Tech-Connect Summit"<br/>}<br/>} |

### Response

**[?Operations\CreateSimulcastOfStreamResponse](../../Models/Operations/CreateSimulcastOfStreamResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## deleteSimulcastOfStream

Deletes a simulcast using its unique simulcastId, which you received during the simulcast creation process. Deleting a simulcast stops the broadcast to the associated platform, while the parent stream continues if it’s live. This action can’t be undone, and you must create a new simulcast to resume streaming to the same platform.

Webhook event: <a href="https://fastpix.com/docs/live-stream-events/live-events#videolive_streamsimulcast_targetdeleted">video.live_stream.simulcast_target.deleted</a>


#### Example
A broadcaster may need to stop simulcasting to one platform while keeping the stream active on others. For example, a tech company is simulcasting a product launch across multiple platforms. Midway through the event, they decide to stop the simulcast on Facebook due to performance issues but continue streaming on YouTube. They use this API to delete the Facebook simulcast target. 

### Example Usage

<!-- UsageSnippet language="php" operationID="delete-simulcast-of-stream" method="delete" path="/live/streams/{streamId}/simulcast/{simulcastId}" -->
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

    // The stream ID and simulcast ID to delete
    $streamId = 'your-stream-id';
    $simulcastId = 'your-simulcast-id';

    $response = $sdk->simulcastStream->deleteSimulcastOfStream(
        streamId: $streamId,
        simulcastId: $simulcastId,
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

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    | Example                                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `streamId`                                                                                                                     | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | After creating a new live stream, FastPix assigns a unique identifier to the stream.                                           | 8717422d89288ad5958d4a86e9afe2a2                                                                                               |
| `simulcastId`                                                                                                                  | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | When you create the new simulcast, FastPix assign a universal unique identifier which can contain a maximum of 255 characters. | 9217422d89288ad5958d4a86e9afe2a1                                                                                               |

### Response

**[?Operations\DeleteSimulcastOfStreamResponse](../../Models/Operations/DeleteSimulcastOfStreamResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## getSpecificSimulcastOfStream

Retrieves the details of a specific simulcast associated with a parent live stream. By providing both the `streamId` of the parent stream and the `simulcastId`, FastPix returns detailed information about the simulcast, such as the stream URL, the status of the simulcast, and metadata. 

#### Example
This endpoint can be used to verify the status of the simulcast on external platforms before the live stream begins. For example, before starting a live gaming event, the organizer wants to ensure that the simulcast to Twitch is set up correctly. They retrieve the simulcast information to confirm that everything is properly configured.

### Example Usage

<!-- UsageSnippet language="php" operationID="get-specific-simulcast-of-stream" method="get" path="/live/streams/{streamId}/simulcast/{simulcastId}" -->
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

    // The stream ID and simulcast ID to retrieve
    $streamId = 'your-stream-id';
    $simulcastId = '8717422d89288ad5958d4a86e9afe2a2';

    $response = $sdk->simulcastStream->getSpecificSimulcastOfStream(
        streamId: $streamId,
        simulcastId: $simulcastId,
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

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    | Example                                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `streamId`                                                                                                                     | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | After creating a new live stream, FastPix assigns a unique identifier to the stream.                                           | 8717422d89288ad5958d4a86e9afe2a2                                                                                               |
| `simulcastId`                                                                                                                  | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | When you create the new simulcast, FastPix assign a universal unique identifier which can contain a maximum of 255 characters. | 8717422d89288ad5958d4a86e9afe2a2                                                                                               |

### Response

**[?Operations\GetSpecificSimulcastOfStreamResponse](../../Models/Operations/GetSpecificSimulcastOfStreamResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## updateSpecificSimulcastOfStream

Updates the status of a specific simulcast linked to a parent live stream. You can enable or disable the simulcast at any time while the parent stream is active or idle. After the live stream is disabled, the simulcast can no longer be modified.

Webhook event: <a href="https://fastpix.com/docs/live-stream-events/live-events#videolive_streamsimulcast_targetupdated">video.live_stream.simulcast_target.updated</a>

#### Example
When a `PATCH` request is made to this endpoint, the API updates the status of the simulcast. This can be useful for pausing or resuming a simulcast on a particular platform without stopping the parent live stream.

### Example Usage

<!-- UsageSnippet language="php" operationID="update-specific-simulcast-of-stream" method="put" path="/live/streams/{streamId}/simulcast/{simulcastId}" -->
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

    // The stream ID and simulcast ID to update
    $streamId = 'your-stream-id';
    $simulcastId = 'your-simulcast-id';

    $body = new Components\SimulcastUpdateRequest(
        metadata: [
            'simulcast_name' => 'Tech today',
        ]);

    $response = $sdk->simulcastStream->updateSpecificSimulcastOfStream(
        body: $body,
        streamId: $streamId,
        simulcastId: $simulcastId,
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

### Parameters

| Parameter                                                                                                                      | Type                                                                                                                           | Required                                                                                                                       | Description                                                                                                                    | Example                                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| `streamId`                                                                                                                     | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | Upon creating a new live stream, FastPix assigns a unique identifier to the stream.                                            | 9714422d89287ad5758d4a86e9afe1a2                                                                                               |
| `simulcastId`                                                                                                                  | *string*                                                                                                                       | :heavy_check_mark:                                                                                                             | When you create the new simulcast, FastPix assign a universal unique identifier which can contain a maximum of 255 characters. | 8717422d89288ad5958d4a86e9afe2a2                                                                                               |
| `body`                                                                                                                         | [Components\SimulcastUpdateRequest](../../Models/Components/SimulcastUpdateRequest.md)                                         | :heavy_check_mark:                                                                                                             | N/A                                                                                                                            | {<br/>"isEnabled": true,<br/>"metadata": {<br/>"simulcast_name": "Tech today"<br/>}<br/>}                                      |

### Response

**[?Operations\UpdateSpecificSimulcastOfStreamResponse](../../Models/Operations/UpdateSpecificSimulcastOfStreamResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\APIException | 4XX, 5XX            | \*/\*               |