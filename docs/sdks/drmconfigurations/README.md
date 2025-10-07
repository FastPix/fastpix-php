# DRMConfigurations
(*drmConfigurations*)

## Overview

### Available Operations

* [getDrmConfiguration](#getdrmconfiguration) - Get list of DRM configuration IDs
* [getDrmConfigurationById](#getdrmconfigurationbyid) - Get DRM configuration by ID

## getDrmConfiguration


This endpoint retrieves the DRM configuration (DRM ID) associated with a workspace. It returns a list of DRM configurations, identified by a unique DRM ID, which is used for creating DRM encrypted asset.

**How it works:**
1. Make a GET request to this endpoint.  
2. Optionally use the `offset` and `limit` query parameters to paginate through the list of DRM configurations.  
3. The response includes a list of DRM IDs and pagination metadata.

**Example:**  
A media service provider may retrieve DRM configuration for a workspace to create DRM content.

Related guide: <a href="https://docs.fastpix.io/docs/secure-playback-with-drm">Manage DRM configuration</a>


### Example Usage

<!-- UsageSnippet language="php" operationID="getDrmConfiguration" method="get" path="/on-demand/drm-configurations" -->
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



$response = $sdk->drmConfigurations->getDrmConfiguration(
    offset: 1,
    limit: 10

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                        | Type                                                                             | Required                                                                         | Description                                                                      | Example                                                                          |
| -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- |
| `offset`                                                                         | *?int*                                                                           | :heavy_minus_sign:                                                               | Offset determines the starting point for data retrieval within a paginated list. | 1                                                                                |
| `limit`                                                                          | *?int*                                                                           | :heavy_minus_sign:                                                               | Limit specifies the maximum number of items to display per page.                 | 10                                                                               |

### Response

**[?Operations\GetDrmConfigurationResponse](../../Models/Operations/GetDrmConfigurationResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\BadRequestException        | 400                               | application/json                  |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ForbiddenException         | 403                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## getDrmConfigurationById


This endpoint retrieves a DRM configuration ID. It is used to fetch the DRM-related ID for a workspace, typically required when validating or applying DRM policies to video assets.

**How it works:**
1. Make a GET request to this endpoint, replacing `{drmConfigurationId}` with the UUID of the DRM configuration.  
2. The response will contain the associated DRM configuration ID.

Related guide: <a href="https://docs.fastpix.io/docs/secure-playback-with-drm">Manage DRM configuration</a>


### Example Usage

<!-- UsageSnippet language="php" operationID="getDrmConfigurationById" method="get" path="/on-demand/drm-configurations/{drmConfigurationId}" -->
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



$response = $sdk->drmConfigurations->getDrmConfigurationById(
    drmConfigurationId: '4fa85f64-5717-4562-b3fc-2c963f66afa6'
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                       | Type                                            | Required                                        | Description                                     | Example                                         |
| ----------------------------------------------- | ----------------------------------------------- | ----------------------------------------------- | ----------------------------------------------- | ----------------------------------------------- |
| `drmConfigurationId`                            | *string*                                        | :heavy_check_mark:                              | The unique identifier of the DRM configuration. | 4fa85f64-5717-4562-b3fc-2c963f66afa6            |

### Response

**[?Operations\GetDrmConfigurationByIdResponse](../../Models/Operations/GetDrmConfigurationByIdResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\BadRequestException        | 400                               | application/json                  |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ForbiddenException         | 403                               | application/json                  |
| Errors\MediaNotFoundException     | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |