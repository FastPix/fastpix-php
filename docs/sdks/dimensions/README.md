# Dimensions
(*dimensions*)

## Overview

### Available Operations

* [listDimensions](#listdimensions) - List the dimensions
* [listFilterValuesForDimension](#listfiltervaluesfordimension) - List the filter values for a dimension

## listDimensions

Retrieves a list of dimensions that can be used as query parameters across various data endpoints. Each dimension has a unique id that can be used to filter data effectively. 

The dimensions retrieved from this endpoint can be used in conjunction with the <a href="https://docs.fastpix.io/reference/list_video_views">list video views</a> and <a href="https://docs.fastpix.io/reference/list_by_top_content">list by top content</a> endpoints to filter results based on specific criteria. For example, you can filter views by `browser_name`, `os_name`, `device_type`, and more.

Related guides: <a href="https://docs.fastpix.io/page/what-video-data-do-we-capture#/">What Video Data do we capture?</a> ,   <a href="https://docs.fastpix.io/docs/user-passable-metadata-1">Use passable dimensions</a>


### Example Usage

<!-- UsageSnippet language="php" operationID="list_dimensions" method="get" path="/data/dimensions" -->
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



$response = $sdk->dimensions->listDimensions(

);

if ($response->object !== null) {
    // handle response
}
```

### Response

**[?Operations\ListDimensionsResponse](../../Models/Operations/ListDimensionsResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\BadRequestException        | 400                               | application/json                  |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## listFilterValuesForDimension

This endpoint returns the filter values associated with a specific dimension, along with the total number of video views for each value. For example, it can list all `browser_name` (dimension) and show how many views occurred for all available browsers like Chrome, Safari (filter values). 


In order to use the <a href="https://docs.fastpix.io/docs/custom-business-metadata">Custom Dimensions</a>, you must enable them in the dashboard under settings option based on the plan you have opted for.

#### Example

A developer wants to know how their video content performs across different browsers. By calling this endpoint for the `device_type` dimension, they can retrieve a breakdown of video views by each device (e.g., Desktop, Mobile, Tablet). This data will help the developer understand where optimizations or troubleshooting may be necessary.


Related guide: <a href="https://docs.fastpix.io/docs/understand-dashboard-ui#filters-and-timeframes">Filters and timeframes</a>


### Example Usage

<!-- UsageSnippet language="php" operationID="list_filter_values_for_dimension" method="get" path="/data/dimensions/{dimensionsId}" -->
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



$response = $sdk->dimensions->listFilterValuesForDimension(
    dimensionsId: Operations\DimensionsId::BrowserName,
    timespan: Operations\ListFilterValuesForDimensionTimespan::Sevendays,
    filterby: 'browser_name:Chrome'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                | Type                                                                                                                                                                                                                                                                                                                     | Required                                                                                                                                                                                                                                                                                                                 | Description                                                                                                                                                                                                                                                                                                              | Example                                                                                                                                                                                                                                                                                                                  |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `dimensionsId`                                                                                                                                                                                                                                                                                                           | [Operations\DimensionsId](../../Models/Operations/DimensionsId.md)                                                                                                                                                                                                                                                       | :heavy_check_mark:                                                                                                                                                                                                                                                                                                       | Pass Dimensions id<br/>                                                                                                                                                                                                                                                                                                  | browser_name                                                                                                                                                                                                                                                                                                             |
| `timespan`                                                                                                                                                                                                                                                                                                               | [Operations\ListFilterValuesForDimensionTimespan](../../Models/Operations/ListFilterValuesForDimensionTimespan.md)                                                                                                                                                                                                       | :heavy_check_mark:                                                                                                                                                                                                                                                                                                       | This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.<br/>                                                                                | 7:days                                                                                                                                                                                                                                                                                                                   |
| `filterby`                                                                                                                                                                                                                                                                                                               | *?string*                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                       | Pass the dimensions and their corresponding values you want to filter the views by. For excluding the values in the filter we can pass '!' before the filter value. The list of filters can be obtained from list of dimensions endpoint.<br/>Example Values : [ browser_name:Chrome , os_name:macOS , device_name:Galaxy ]<br/> | browser_name:Chrome                                                                                                                                                                                                                                                                                                      |

### Response

**[?Operations\ListFilterValuesForDimensionResponse](../../Models/Operations/ListFilterValuesForDimensionResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ViewNotFoundException      | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |