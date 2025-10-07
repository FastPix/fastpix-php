# Metrics
(*metrics*)

## Overview

### Available Operations

* [listBreakdownValues](#listbreakdownvalues) - List breakdown values
* [listOverallValues](#listoverallvalues) - List overall values
* [getTimeseriesData](#gettimeseriesdata) - Get timeseries data
* [listComparisonValues](#listcomparisonvalues) - List comparison values

## listBreakdownValues

Retrieves breakdown values for a specified metric and timespan, allowing you to analyze the performance of your content based on various dimensions. It provides insights into how different factors contribute to the overall metrics. 

#### How it works

  1. Before using this endpoint, you can call the <a href="https://docs.fastpix.io/reference/list_dimensions">List Dimensions</a> endpoint to retrieve all available dimensions that can be used in your query. 

  2. Make a `GET` request to this endpoint with the required `metricId` and other query parameters. 

  3. Receive a response containing the breakdown values for the specified metric, grouped and filtered according to your parameters. 

  4. Upon successful retrieval, the response will include the breakdown values based on the specified parameters. Note that the time values ( `totalWatchTime` and `totalPlayingTime` ) are in milliseconds. 


#### Example


A developer wants to analyze how watch time varies across different device types. By calling this endpoint for the `playing_time` metric and filtering by `device_type`, they can understand how engagement differs between mobile, desktop, and tablet users. This data will guide optimization efforts for different platforms. 


Related guide: <a href="https://docs.fastpix.io/docs/metrics-overview">Understand data definitions</a>


### Example Usage

<!-- UsageSnippet language="php" operationID="list_breakdown_values" method="get" path="/data/metrics/{metricId}/breakdown" -->
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

$request = new Operations\ListBreakdownValuesRequest(
    metricId: Operations\ListBreakdownValuesMetricId::QualityOfExperienceScore,
    timespan: Operations\ListBreakdownValuesTimespan::Sevendays,
    filterby: 'browser_name:Chrome',
    groupBy: 'browser_name',
);

$response = $sdk->metrics->listBreakdownValues(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                      | Type                                                                                           | Required                                                                                       | Description                                                                                    |
| ---------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------- |
| `$request`                                                                                     | [Operations\ListBreakdownValuesRequest](../../Models/Operations/ListBreakdownValuesRequest.md) | :heavy_check_mark:                                                                             | The request object to use for the request.                                                     |

### Response

**[?Operations\ListBreakdownValuesResponse](../../Models/Operations/ListBreakdownValuesResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ViewNotFoundException      | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## listOverallValues

Retrieves overall values for a specified metric, providing summary statistics that help you understand the performance of your content. The response includes key metrics such as `totalWatchTime`, `uniqueViews`, `totalPlayTime` and `totalViews`. 

#### How it works

  1. Before using this endpoint, you can call the <a href="https://docs.fastpix.io/reference/list_dimensions">list dimensions</a> endpoint to retrieve all available dimensions that can be used in your query. 

  2. Make a `GET` request to this endpoint with the required `metricId` and other query parameters. 

  3. Receive a response containing the overall values for the specified metric, which may vary based on the applied filters. 






#### Key fields in response


  * **value:** The specific metric value calculated based on the applied filters. 
  * **totalWatchTime:** Total time watched across all views, represented in milliseconds. 
  * **uniqueViews:** The count of unique viewers who interacted with the content. 
  * **totalViews:** The total number of views recorded. 
  * **totalPlayTime:** Total time spent playing the video, represented in milliseconds. 
  * **globalValue:** A global metric value that reflects the overall performance of the specified metric across the entire dataset for the given timespan. This value is not affected by specific filters. 


  Related guide: <a href="https://docs.fastpix.io/docs/metrics-overview">Understand data definitions</a>


### Example Usage

<!-- UsageSnippet language="php" operationID="list_overall_values" method="get" path="/data/metrics/{metricId}/overall" -->
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



$response = $sdk->metrics->listOverallValues(
    metricId: Operations\ListOverallValuesMetricId::QualityOfExperienceScore,
    timespan: Operations\ListOverallValuesTimespan::Sevendays,
    measurement: 'avg',
    filterby: 'browser_name:Chrome'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                | Type                                                                                                                                                                                                                                                                                                                     | Required                                                                                                                                                                                                                                                                                                                 | Description                                                                                                                                                                                                                                                                                                              | Example                                                                                                                                                                                                                                                                                                                  |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `metricId`                                                                                                                                                                                                                                                                                                               | [Operations\ListOverallValuesMetricId](../../Models/Operations/ListOverallValuesMetricId.md)                                                                                                                                                                                                                             | :heavy_check_mark:                                                                                                                                                                                                                                                                                                       | Pass metric Id<br/>                                                                                                                                                                                                                                                                                                      | quality_of_experience_score                                                                                                                                                                                                                                                                                              |
| `timespan`                                                                                                                                                                                                                                                                                                               | [Operations\ListOverallValuesTimespan](../../Models/Operations/ListOverallValuesTimespan.md)                                                                                                                                                                                                                             | :heavy_check_mark:                                                                                                                                                                                                                                                                                                       | This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.<br/>                                                                                | 7:days                                                                                                                                                                                                                                                                                                                   |
| `measurement`                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                       | The measurement for the given metrics.<br/>Possible Values : [95th, median, avg, count or sum]<br/>                                                                                                                                                                                                                      | avg                                                                                                                                                                                                                                                                                                                      |
| `filterby`                                                                                                                                                                                                                                                                                                               | *?string*                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                       | Pass the dimensions and their corresponding values you want to filter the views by. For excluding the values in the filter we can pass '!' before the filter value. The list of filters can be obtained from list of dimensions endpoint.<br/>Example Values : [ browser_name:Chrome , os_name:macOS , device_name:Galaxy ]<br/> | browser_name:Chrome                                                                                                                                                                                                                                                                                                      |

### Response

**[?Operations\ListOverallValuesResponse](../../Models/Operations/ListOverallValuesResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ViewNotFoundException      | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## getTimeseriesData

This endpoint retrieves timeseries data for a specified metric, providing insights into how the metric values change over time. The response includes an array of data points, each representing the metric's value at specific intervals. 

Each data point contains the following fields: 

* **intervalTime:** The timestamp for the data point indicating when the metric value was recorded. 
* **metricValue:** The value of the specified metric at the given interval, reflecting the performance or engagement level during that time. 
* **numberOfViews:** The total number of views recorded during that interval, providing context for the metric value. 


### Example Usage

<!-- UsageSnippet language="php" operationID="get_timeseries_data" method="get" path="/data/metrics/{metricId}/timeseries" -->
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

$request = new Operations\GetTimeseriesDataRequest(
    metricId: Operations\GetTimeseriesDataMetricId::QualityOfExperienceScore,
    timespan: Operations\GetTimeseriesDataTimespan::Sevendays,
    filterby: 'browser_name:Chrome',
);

$response = $sdk->metrics->getTimeseriesData(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                  | Type                                                                                       | Required                                                                                   | Description                                                                                |
| ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ |
| `$request`                                                                                 | [Operations\GetTimeseriesDataRequest](../../Models/Operations/GetTimeseriesDataRequest.md) | :heavy_check_mark:                                                                         | The request object to use for the request.                                                 |

### Response

**[?Operations\GetTimeseriesDataResponse](../../Models/Operations/GetTimeseriesDataResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ViewNotFoundException      | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## listComparisonValues

This endpoint allows you to compare multiple metrics across specified dimensions. You can specify the metrics you want to compare in the query parameters, and the response will include the relevant metrics for the specified dimensions. 

#### How it works 

  1. Before making a request to this endpoint, call the <a href="https://docs.fastpix.io/reference/list_dimensions">list dimensions</a> endpoint to obtain all available dimensions that can be used for comparison. 

  2. Make a `GET` request to this endpoint with the desired metrics specified in the query parameters. 

  3. Receive a response containing the comparison values for the specified metrics across the selected dimensions. 


  Related guide: <a href="https://docs.fastpix.io/docs/understand-dashboard-ui#compare-metrics">Compare metrics in dashboard</a>


### Example Usage

<!-- UsageSnippet language="php" operationID="list_comparison_values" method="get" path="/data/metrics/comparison" -->
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



$response = $sdk->metrics->listComparisonValues(
    timespan: Operations\ListComparisonValuesTimespan::Sevendays,
    filterby: 'browser_name:Chrome',
    dimension: Operations\ListComparisonValuesDimension::BrowserName,
    value: 'Chrome'

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                | Type                                                                                                                                                                                                                                                                                                                     | Required                                                                                                                                                                                                                                                                                                                 | Description                                                                                                                                                                                                                                                                                                              | Example                                                                                                                                                                                                                                                                                                                  |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `timespan`                                                                                                                                                                                                                                                                                                               | [Operations\ListComparisonValuesTimespan](../../Models/Operations/ListComparisonValuesTimespan.md)                                                                                                                                                                                                                       | :heavy_check_mark:                                                                                                                                                                                                                                                                                                       | This parameter specifies the time span between which the video views list should be retrieved by. You can provide either from and to unix epoch timestamps or time duration. The scope of duration is between 60 minutes to 30 days.<br/>                                                                                | 7:days                                                                                                                                                                                                                                                                                                                   |
| `filterby`                                                                                                                                                                                                                                                                                                               | *?string*                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                       | Pass the dimensions and their corresponding values you want to filter the views by. For excluding the values in the filter we can pass '!' before the filter value. The list of filters can be obtained from list of dimensions endpoint.<br/>Example Values : [ browser_name:Chrome , os_name:macOS , device_name:Galaxy ]<br/> | browser_name:Chrome                                                                                                                                                                                                                                                                                                      |
| `dimension`                                                                                                                                                                                                                                                                                                              | [?Operations\ListComparisonValuesDimension](../../Models/Operations/ListComparisonValuesDimension.md)                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                       | The dimension id in which the views are watched.<br/>                                                                                                                                                                                                                                                                    | browser_name                                                                                                                                                                                                                                                                                                             |
| `value`                                                                                                                                                                                                                                                                                                                  | *?string*                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                       | The value for the selected dimension. <br/>For example:<br/> If `dimension` is `browser_name`, the value could be  `Chrome` `,` `Firefox` `etc` .<br/> If `dimension` is `os_name`, the value could be `macOS` `,` `Windows` `etc` .<br/>                                                                                | Chrome                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\ListComparisonValuesResponse](../../Models/Operations/ListComparisonValuesResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ViewNotFoundException      | 404                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |