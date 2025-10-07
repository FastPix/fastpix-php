# Playback
(*playback*)

## Overview

### Available Operations

* [createMediaPlaybackId](#createmediaplaybackid) - Create a playback ID
* [deleteMediaPlaybackId](#deletemediaplaybackid) - Delete a playback ID
* [getPlaybackId](#getplaybackid) - Get a playback ID

## createMediaPlaybackId

You can create a new playback ID for a specific media asset. If you have already retrieved an existing `playbackId` using the <a href="https://docs.fastpix.io/reference/get-media">Get Media by ID</a> endpoint for a media asset, you can use this endpoint to generate a new playback ID with a specified access policy. 



If you want to create a private playback ID for a media asset that already has a public playback ID, this endpoint also allows you to do so by specifying the desired access policy. 

#### How it works

1. Make a `POST` request to this endpoint, replacing `<mediaId>` with the `uploadId` or `id` of the media asset. 

2. Include the `accessPolicy` in the request body with `private` or `public` as the value. 

3. Receive a response containing the newly created playback ID with the requested access level. 


#### Example
A video streaming service generates playback IDs for each media file when users request to view specific content. The playback ID is then used by the video player to stream the video.


### Example Usage

<!-- UsageSnippet language="php" operationID="create-media-playback-id" method="post" path="/on-demand/{mediaId}/playback-ids" -->
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

$requestBody = new Operations\CreateMediaPlaybackIdRequestBody(
    accessPolicy: Components\AccessPolicy::Public,
    drmConfigurationId: '123e4567-e89b-12d3-a456-426614174000',
    resolution: Operations\Resolution::OneThousandAndEightyp,
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
| `requestBody`                                                                                                     | [?Operations\CreateMediaPlaybackIdRequestBody](../../Models/Operations/CreateMediaPlaybackIdRequestBody.md)       | :heavy_minus_sign:                                                                                                | Request body for creating playback id for an media                                                                |                                                                                                                   |

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

This endpoint allows you to remove a specific playback ID associated with a media asset. Deleting a `playbackId` will revoke access to the media content linked to that ID. 


#### How it works

1. Make a `DELETE` request to this endpoint, replacing `<mediaId>` with the unique ID of the media asset from which you want to delete the playback ID. 

2. Specify the `playbackId` you wish to delete in the request body. 

#### Example

Your platform offers limited-time access to premium content. When the subscription expires, you can revoke access to the content by deleting the associated playback ID, preventing users from streaming the video further.


### Example Usage

<!-- UsageSnippet language="php" operationID="delete-media-playback-id" method="delete" path="/on-demand/{mediaId}/playback-ids" -->
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

## getPlaybackId

This endpoint retrieves details about a specific playback ID associated with a media asset. This endpoint is commonly used to check the access policy (e.g., public or private) with the specific playback ID.

**How it works:**
1. Make a GET request to the endpoint, replacing `{mediaId}` with the `id` of the media, and `{playbackId}` with the specific playback ID.
2. Useful for auditing or validation before granting playback access in your application.

**Example:**
A media platform might use this endpoint to verify if a playback ID is public or private before embedding the video in a frontend player or allowing access to a restricted group.


### Example Usage

<!-- UsageSnippet language="php" operationID="get-playback-id" method="get" path="/on-demand/{mediaId}/playback-ids/{playbackId}" -->
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



$response = $sdk->playback->getPlaybackId(
    mediaId: '4fa85f64-5717-4562-b3fc-2c963f66afa6',
    playbackId: '4fa85f64-5717-4562-b3fc-2c963f66afa6'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                            | Type                                 | Required                             | Description                          | Example                              |
| ------------------------------------ | ------------------------------------ | ------------------------------------ | ------------------------------------ | ------------------------------------ |
| `mediaId`                            | *string*                             | :heavy_check_mark:                   | N/A                                  | 4fa85f64-5717-4562-b3fc-2c963f66afa6 |
| `playbackId`                         | *string*                             | :heavy_check_mark:                   | N/A                                  | 4fa85f64-5717-4562-b3fc-2c963f66afa6 |

### Response

**[?Operations\GetPlaybackIdResponse](../../Models/Operations/GetPlaybackIdResponse.md)**

### Errors

| Error Type                              | Status Code                             | Content Type                            |
| --------------------------------------- | --------------------------------------- | --------------------------------------- |
| Errors\InvalidPermissionException       | 401                                     | application/json                        |
| Errors\ForbiddenException               | 403                                     | application/json                        |
| Errors\MediaOrPlaybackNotFoundException | 404                                     | application/json                        |
| Errors\ValidationErrorResponse          | 422                                     | application/json                        |
| Errors\APIException                     | 4XX, 5XX                                | \*/\*                                   |