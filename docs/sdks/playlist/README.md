# Playlist
(*playlist*)

## Overview

### Available Operations

* [createAPlaylist](#createaplaylist) - Create a new playlist
* [getAllPlaylists](#getallplaylists) - Get all playlists
* [getPlaylistById](#getplaylistbyid) - Get a playlist by ID
* [updateAPlaylist](#updateaplaylist) - Update a playlist by ID
* [deleteAPlaylist](#deleteaplaylist) - Delete a playlist by ID
* [addMediaToPlaylist](#addmediatoplaylist) - Add media to a playlist by ID
* [changeMediaOrderInPlaylist](#changemediaorderinplaylist) - Change media order in a playlist by ID
* [deleteMediaFromPlaylist](#deletemediafromplaylist) - Delete media in a playlist by ID

## createAPlaylist

This endpoint creates a new playlist within a specified workspace. A playlist acts as a container for organizing media items either manually or based on filters and metadata. <br> <br>
### Playlists can be created in two modes
- **Manual:** An empty playlist is created without any initial media items. It's intended for manual curation, where items can be added later in a user-defined sequence.
- **Smart:** The playlist is auto-populated at creation time based on filters (video creation date range) criteria provided in the request.

#### How it works 

 - When a user sends a POST request to this endpoint, FastPix creates a playlist and returns a playlist ID, using which items can be added later in a user-defined sequence.
 - For a smart playlist, the playlist will be auto-populated based on metadata in the request body.


#### Example
An e-learning platform creates a new playlist titled "Beginner Python Series" via the API. The response includes a unique playlist ID. The platform then uses this ID to add a series of video tutorials to the playlist in a defined order. The playlist is presented to learners on the frontend as a structured learning path.

### Example Usage

<!-- UsageSnippet language="php" operationID="create-a-playlist" method="post" path="/on-demand/playlists" -->
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

$request = new Components\CreatePlaylistRequest(
    name: 'playlist name',
    referenceId: 'a1',
    type: Components\CreatePlaylistRequestType::Smart,
    description: 'This is a playlist',
    playOrder: Components\PlaylistOrder::CreatedDateASC,
    limit: 20,
    metadata: new Components\CreatePlaylistRequestMetadata(
        createdDate: new Components\DateRange(
            startDate: '2024-11-11',
            endDate: '2024-12-12',
        ),
        updatedDate: new Components\DateRange(
            startDate: '2024-11-11',
            endDate: '2024-12-12',
        ),
    ),
);

$response = $sdk->playlist->createAPlaylist(
    request: $request
);

if ($response->playlistCreatedResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                            | Type                                                                                 | Required                                                                             | Description                                                                          |
| ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ |
| `$request`                                                                           | [Components\CreatePlaylistRequest](../../Models/Components/CreatePlaylistRequest.md) | :heavy_check_mark:                                                                   | The request object to use for the request.                                           |

### Response

**[?Operations\CreateAPlaylistResponse](../../Models/Operations/CreateAPlaylistResponse.md)**

### Errors

| Error Type                               | Status Code                              | Content Type                             |
| ---------------------------------------- | ---------------------------------------- | ---------------------------------------- |
| Errors\UnauthorizedException             | 401                                      | application/json                         |
| Errors\InvalidPermissionException        | 403                                      | application/json                         |
| Errors\DuplicateReferenceIdErrorResponse | 409                                      | application/json                         |
| Errors\ValidationErrorResponse           | 422                                      | application/json                         |
| Errors\APIException                      | 4XX, 5XX                                 | \*/\*                                    |

## getAllPlaylists

This endpoint retrieves all playlists present within a specified workspace. It allows users to view the collection of playlists that have been created, whether manual or smart, along with their associated metadata.
#### How it works

 - When a user sends a GET request to this endpoint, FastPix returns a list of all playlists in the workspace, including details such as playlist IDs, titles, creation mode (manual or smart), and other relevant metadata.
 
#### Example

  An e-learning platform requests all playlists within a workspace to display an overview of available learning paths. The response includes multiple playlists like "Beginner Python Series" and "Advanced Java Tutorials," enabling the platform to show users a catalog of curated content collections.

### Example Usage

<!-- UsageSnippet language="php" operationID="get-all-playlists" method="get" path="/on-demand/playlists" -->
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



$response = $sdk->playlist->getAllPlaylists(
    limit: 1,
    offset: 1

);

if ($response->getAllPlaylistsResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                               | Type                                                                                    | Required                                                                                | Description                                                                             | Example                                                                                 |
| --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------- |
| `limit`                                                                                 | *?int*                                                                                  | :heavy_minus_sign:                                                                      | The number of playlists to return (default is 10, max is 50).                           | 1                                                                                       |
| `offset`                                                                                | *?int*                                                                                  | :heavy_minus_sign:                                                                      | The page number to retrieve, starting from 1. Used for paginating the playlist results. | 1                                                                                       |

### Response

**[?Operations\GetAllPlaylistsResponse](../../Models/Operations/GetAllPlaylistsResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\UnauthorizedException | 401                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## getPlaylistById

This endpoint retrieves detailed information about a specific playlist using its unique `playlistId`. It provides comprehensive metadata about the playlist, including its title, creation mode (manual or smart), media items along with the metadata of each media in the playlist.

 
#### Example
An e-learning platform requests details for the playlist "Beginner Python Series" by providing its unique `playlistId`. The response includes the playlist's title, creation mode, and the ordered list of video tutorials contained within, enabling the platform to present the full learning path to users.

### Example Usage

<!-- UsageSnippet language="php" operationID="get-playlist-by-id" method="get" path="/on-demand/playlists/{playlistId}" -->
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



$response = $sdk->playlist->getPlaylistById(
    playlistId: '<id>'
);

if ($response->playlistByIdResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                           | Type                                                | Required                                            | Description                                         |
| --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------- |
| `playlistId`                                        | *string*                                            | :heavy_check_mark:                                  | The unique id of the playlist you want to retrieve. |

### Response

**[?Operations\GetPlaylistByIdResponse](../../Models/Operations/GetPlaylistByIdResponse.md)**

### Errors

| Error Type                                | Status Code                               | Content Type                              |
| ----------------------------------------- | ----------------------------------------- | ----------------------------------------- |
| Errors\UnauthorizedException              | 401                                       | application/json                          |
| Errors\NotFoundError                      | 404                                       | application/json                          |
| Errors\InvalidPlaylistIdResponseException | 422                                       | application/json                          |
| Errors\APIException                       | 4XX, 5XX                                  | \*/\*                                     |

## updateAPlaylist

This endpoint allows you to update the name and description of an existing playlist. It enables modifications to the playlist's metadata without altering the media items or playlist structure.
#### How it works

 - When a user sends a PUT request to this endpoint with the `playlistId` and updated name and description in the request body, FastPix updates the playlist metadata accordingly and returns the updated playlist details.

#### Example
An e-learning platform updates the playlist titled "Beginner Python Series" to rename it as "Python Basics" and add a more detailed description. The updated metadata is reflected when retrieving the playlist, helping users better understand the playlist content.

### Example Usage

<!-- UsageSnippet language="php" operationID="update-a-playlist" method="put" path="/on-demand/playlists/{playlistId}" -->
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

$updatePlaylistRequest = new Components\UpdatePlaylistRequest(
    name: 'updated name',
    description: 'updated description',
);

$response = $sdk->playlist->updateAPlaylist(
    playlistId: '<id>',
    updatePlaylistRequest: $updatePlaylistRequest

);

if ($response->playlistCreatedResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                            | Type                                                                                 | Required                                                                             | Description                                                                          | Example                                                                              |
| ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ |
| `playlistId`                                                                         | *string*                                                                             | :heavy_check_mark:                                                                   | The unique id of the playlist you want to retrieve.                                  |                                                                                      |
| `updatePlaylistRequest`                                                              | [Components\UpdatePlaylistRequest](../../Models/Components/UpdatePlaylistRequest.md) | :heavy_check_mark:                                                                   | N/A                                                                                  | {<br/>"name": "updated name",<br/>"description": "updated description"<br/>}         |

### Response

**[?Operations\UpdateAPlaylistResponse](../../Models/Operations/UpdateAPlaylistResponse.md)**

### Errors

| Error Type                                | Status Code                               | Content Type                              |
| ----------------------------------------- | ----------------------------------------- | ----------------------------------------- |
| Errors\UnauthorizedException              | 401                                       | application/json                          |
| Errors\InvalidPermissionException         | 403                                       | application/json                          |
| Errors\InvalidPlaylistIdResponseException | 422                                       | application/json                          |
| Errors\APIException                       | 4XX, 5XX                                  | \*/\*                                     |

## deleteAPlaylist

This endpoint allows you to delete an existing playlist from the workspace. Once deleted, the playlist and its metadata are permanently removed and cannot be recovered.
#### How it works
 - When a user sends a DELETE request to this endpoint with the `playlistId`, FastPix removes the specified playlist from the workspace and returns a confirmation of successful deletion.
 
#### Example
An e-learning platform deletes an outdated playlist titled "Old Python Tutorials" by providing its unique playlist ID. The platform receives confirmation that the playlist has been removed, ensuring learners no longer see the obsolete content.

### Example Usage

<!-- UsageSnippet language="php" operationID="delete-a-playlist" method="delete" path="/on-demand/playlists/{playlistId}" -->
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



$response = $sdk->playlist->deleteAPlaylist(
    playlistId: '<id>'
);

if ($response->successResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                         | Type                                              | Required                                          | Description                                       |
| ------------------------------------------------- | ------------------------------------------------- | ------------------------------------------------- | ------------------------------------------------- |
| `playlistId`                                      | *string*                                          | :heavy_check_mark:                                | The unique id of the playlist you want to delete. |

### Response

**[?Operations\DeleteAPlaylistResponse](../../Models/Operations/DeleteAPlaylistResponse.md)**

### Errors

| Error Type                                | Status Code                               | Content Type                              |
| ----------------------------------------- | ----------------------------------------- | ----------------------------------------- |
| Errors\UnauthorizedException              | 401                                       | application/json                          |
| Errors\InvalidPermissionException         | 403                                       | application/json                          |
| Errors\NotFoundError                      | 404                                       | application/json                          |
| Errors\InvalidPlaylistIdResponseException | 422                                       | application/json                          |
| Errors\APIException                       | 4XX, 5XX                                  | \*/\*                                     |

## addMediaToPlaylist

This endpoint allows you to add one or more media items to an existing playlist. By passing the media ID(s) in the request, the specified media items are appended to the playlist in the order provided.
#### How it works

 - When a user sends a PATCH request to this endpoint with the `playlistId` as path parameter and a list of media ID(s) in the request body, FastPix adds the specified media items to the playlist and returns the updated playlist details.
 
#### Example
An e-learning platform adds new video tutorials to the "Beginner Python Series" playlist by sending their media IDs in the request. The playlist is updated with the new content, ensuring learners have access to the latest tutorials in sequence.

### Example Usage

<!-- UsageSnippet language="php" operationID="add-media-to-playlist" method="patch" path="/on-demand/playlists/{playlistId}/media" -->
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

$mediaIdsRequest = new Components\MediaIdsRequest(
    mediaIds: [
        'a1cd180e-f9b5-4e99-9d44-b9c9baabad89',
        '245800c3-7b73-47d9-a201-e961260dcb30',
        '41316aac-5396-4278-8f44-08d5f2495b12',
    ],
);

$response = $sdk->playlist->addMediaToPlaylist(
    playlistId: '<id>',
    mediaIdsRequest: $mediaIdsRequest

);

if ($response->playlistByIdResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                | Type                                                                     | Required                                                                 | Description                                                              |
| ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ |
| `playlistId`                                                             | *string*                                                                 | :heavy_check_mark:                                                       | The unique id of the playlist you want to perform the operation on.      |
| `mediaIdsRequest`                                                        | [Components\MediaIdsRequest](../../Models/Components/MediaIdsRequest.md) | :heavy_check_mark:                                                       | N/A                                                                      |

### Response

**[?Operations\AddMediaToPlaylistResponse](../../Models/Operations/AddMediaToPlaylistResponse.md)**

### Errors

| Error Type                                | Status Code                               | Content Type                              |
| ----------------------------------------- | ----------------------------------------- | ----------------------------------------- |
| Errors\UnauthorizedException              | 401                                       | application/json                          |
| Errors\InvalidPermissionException         | 403                                       | application/json                          |
| Errors\NotFoundError                      | 404                                       | application/json                          |
| Errors\InvalidPlaylistIdResponseException | 422                                       | application/json                          |
| Errors\APIException                       | 4XX, 5XX                                  | \*/\*                                     |

## changeMediaOrderInPlaylist

This endpoint allows you to change the order of media items within a playlist. By passing the complete list of media IDs in the desired sequence, the playlist's play order is updated accordingly.
#### How it works

 - When a user sends a PUT request to this endpoint with the `playlistId` as path parameter and the reordered list of all media IDs in the request body, FastPix updates the playlist to reflect the new media sequence and returns the updated playlist details.
 
#### Example
An e-learning platform rearranges the "Beginner Python Series" playlist by submitting a reordered list of media IDs. The playlist now follows the new sequence, providing learners with a better structured learning path.

### Example Usage

<!-- UsageSnippet language="php" operationID="change-media-order-in-playlist" method="put" path="/on-demand/playlists/{playlistId}/media" -->
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

$mediaIdsRequest = new Components\MediaIdsRequest(
    mediaIds: [
        'a1cd180e-f9b5-4e99-9d44-b9c9baabad89',
        '245800c3-7b73-47d9-a201-e961260dcb30',
        '41316aac-5396-4278-8f44-08d5f2495b12',
    ],
);

$response = $sdk->playlist->changeMediaOrderInPlaylist(
    playlistId: '<id>',
    mediaIdsRequest: $mediaIdsRequest

);

if ($response->playlistByIdResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                | Type                                                                     | Required                                                                 | Description                                                              |
| ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ |
| `playlistId`                                                             | *string*                                                                 | :heavy_check_mark:                                                       | The unique id of the playlist you want to perform the operation on.      |
| `mediaIdsRequest`                                                        | [Components\MediaIdsRequest](../../Models/Components/MediaIdsRequest.md) | :heavy_check_mark:                                                       | N/A                                                                      |

### Response

**[?Operations\ChangeMediaOrderInPlaylistResponse](../../Models/Operations/ChangeMediaOrderInPlaylistResponse.md)**

### Errors

| Error Type                                | Status Code                               | Content Type                              |
| ----------------------------------------- | ----------------------------------------- | ----------------------------------------- |
| Errors\UnauthorizedException              | 401                                       | application/json                          |
| Errors\InvalidPermissionException         | 403                                       | application/json                          |
| Errors\NotFoundError                      | 404                                       | application/json                          |
| Errors\InvalidPlaylistIdResponseException | 422                                       | application/json                          |
| Errors\APIException                       | 4XX, 5XX                                  | \*/\*                                     |

## deleteMediaFromPlaylist

This endpoint allows you to delete one or more media items from an existing playlist. By passing the media ID(s) in the request, the specified media items are removed from the playlist.
#### How it works

 - When a user sends a DELETE request to this endpoint with the playlist ID as the path parameter and the media ID(s) to be removed in the request body, FastPix deletes the specified media items from the playlist and returns the updated playlist details.
 
#### Example
An e-learning platform removes outdated video tutorials from the "Beginner Python Series" playlist by specifying their media IDs in the request. The playlist is updated to exclude these items, ensuring learners only access relevant content.

### Example Usage

<!-- UsageSnippet language="php" operationID="delete-media-from-playlist" method="delete" path="/on-demand/playlists/{playlistId}/media" -->
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

$mediaIdsRequest = new Components\MediaIdsRequest(
    mediaIds: [
        'a1cd180e-f9b5-4e99-9d44-b9c9baabad89',
        '245800c3-7b73-47d9-a201-e961260dcb30',
        '41316aac-5396-4278-8f44-08d5f2495b12',
    ],
);

$response = $sdk->playlist->deleteMediaFromPlaylist(
    playlistId: '<id>',
    mediaIdsRequest: $mediaIdsRequest

);

if ($response->playlistByIdResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                 | Type                                                                      | Required                                                                  | Description                                                               |
| ------------------------------------------------------------------------- | ------------------------------------------------------------------------- | ------------------------------------------------------------------------- | ------------------------------------------------------------------------- |
| `playlistId`                                                              | *string*                                                                  | :heavy_check_mark:                                                        | The unique id of the playlist you want to perform the operation on.       |
| `mediaIdsRequest`                                                         | [?Components\MediaIdsRequest](../../Models/Components/MediaIdsRequest.md) | :heavy_minus_sign:                                                        | N/A                                                                       |

### Response

**[?Operations\DeleteMediaFromPlaylistResponse](../../Models/Operations/DeleteMediaFromPlaylistResponse.md)**

### Errors

| Error Type                                | Status Code                               | Content Type                              |
| ----------------------------------------- | ----------------------------------------- | ----------------------------------------- |
| Errors\UnauthorizedException              | 401                                       | application/json                          |
| Errors\InvalidPermissionException         | 403                                       | application/json                          |
| Errors\NotFoundError                      | 404                                       | application/json                          |
| Errors\InvalidPlaylistIdResponseException | 422                                       | application/json                          |
| Errors\APIException                       | 4XX, 5XX                                  | \*/\*                                     |