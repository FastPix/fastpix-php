<?php



declare(strict_types=1);

namespace FastPix\Sdk;

use FastPix\Sdk\Hooks\HookContext;
use FastPix\Sdk\Models\Operations;
use FastPix\Sdk\Utils\Options;
use FastPix\Sdk\Utils\Retry;
use FastPix\Sdk\Utils\Retry\RetryUtils;
use Speakeasy\Serializer\DeserializationContext;

class Metrics
{
    private SDKConfiguration $sdkConfiguration;
    /**
     * @param  SDKConfiguration  $sdkConfig
     */
    public function __construct(public SDKConfiguration $sdkConfig)
    {
        $this->sdkConfiguration = $sdkConfig;
    }
    /**
     * @param  string  $baseUrl
     * @param  array<string, string>  $urlVariables
     *
     * @return string
     */
    public function getUrl(string $baseUrl, array $urlVariables): string
    {
        $serverDetails = $this->sdkConfiguration->getServerDetails();

        if ($baseUrl == null) {
            $baseUrl = $serverDetails->baseUrl;
        }

        if ($urlVariables == null) {
            $urlVariables = $serverDetails->options;
        }

        return Utils\Utils::templateUrl($baseUrl, $urlVariables);
    }

    /**
     * Get timeseries data
     *
     * This endpoint retrieves timeseries data for a specified metric, providing insights into how the metric values change over time. The response includes an array of data points, each representing the metric's value at specific intervals. 
     *
     * Each data point contains the following fields: 
     *
     * * **intervalTime:** The timestamp for the data point indicating when the metric value was recorded. 
     * * **metricValue:** The value of the specified metric at the given interval, reflecting the performance or engagement level during that time. 
     * * **numberOfViews:** The total number of views recorded during that interval, providing context for the metric value. 
     *
     *
     * @param  Operations\GetTimeseriesDataRequest  $request
     * @return Operations\GetTimeseriesDataResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function getTimeseriesData(Operations\GetTimeseriesDataRequest $request, ?Options $options = null): Operations\GetTimeseriesDataResponse
    {
        $retryConfig = null;
        if ($options) {
            $retryConfig = $options->retryConfig;
        }
        if ($retryConfig === null && $this->sdkConfiguration->retryConfig) {
            $retryConfig = $this->sdkConfiguration->retryConfig;
        } else {
            $retryConfig = new Retry\RetryConfigBackoff(
                initialIntervalMs: 1000,
                maxIntervalMs: 10000,
                exponent: 1.5,
                maxElapsedTimeMs: 3600000,
                retryConnectionErrors: true,
            );
        }
        $retryCodes = null;
        if ($options) {
            $retryCodes = $options->retryCodes;
        }
        if ($retryCodes === null) {
            $retryCodes = [
                '408',
                '429',
                '500',
                '502',
                '503',
                '504',
            ];
        }
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/data/metrics/{metricId}/timeseries', Operations\GetTimeseriesDataRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];

        $qp = Utils\Utils::getQueryParams(Operations\GetTimeseriesDataRequest::class, $request, $urlOverride);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'get_timeseries_data', [], $this->sdkConfiguration->securitySource);
        $httpRequest = $this->sdkConfiguration->hooks->beforeRequest(new Hooks\BeforeRequestContext($hookContext), $httpRequest);
        $httpOptions['query'] = Utils\QueryParameters::standardizeQueryParams($httpRequest, $qp);
        $httpOptions = Utils\Utils::convertHeadersToOptions($httpRequest, $httpOptions);
        $httpRequest = Utils\Utils::removeHeaders($httpRequest);
        try {
            $httpResponse = RetryUtils::retryWrapper(fn () => $this->sdkConfiguration->client->send($httpRequest, $httpOptions), $retryConfig, $retryCodes);
        } catch (\GuzzleHttp\Exception\GuzzleException $error) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), null, $error);
            $httpResponse = $res;
        }
        $contentType = $httpResponse->getHeader('Content-Type')[0] ?? '';

        $statusCode = $httpResponse->getStatusCode();
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '404', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Operations\GetTimeseriesDataResponseBody', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\GetTimeseriesDataResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    object: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPermissionException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['404'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\ViewNotFoundException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['422'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\ValidationErrorResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['4XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['5XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } else {
            throw new \FastPix\Sdk\Models\Errors\APIException('Unknown status code received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        }
    }

    /**
     * List breakdown values
     *
     * Retrieves breakdown values for a specified metric and timespan, allowing you to analyze the performance of your content based on various dimensions. It provides insights into how different factors contribute to the overall metrics. 
     *
     * #### How it works
     *
     *   1. Before using this endpoint, you can call the <a href="https://docs.fastpix.io/reference/list_dimensions">List Dimensions</a> endpoint to retrieve all available dimensions that can be used in your query. 
     *
     *   2. Make a `GET` request to this endpoint with the required `metricId` and other query parameters. 
     *
     *   3. Receive a response containing the breakdown values for the specified metric, grouped and filtered according to your parameters. 
     *
     *   4. Upon successful retrieval, the response will include the breakdown values based on the specified parameters. Note that the time values ( `totalWatchTime` and `totalPlayingTime` ) are in milliseconds. 
     *
     *
     * #### Example
     *
     *
     * A developer wants to analyze how watch time varies across different device types. By calling this endpoint for the `playing_time` metric and filtering by `device_type`, they can understand how engagement differs between mobile, desktop, and tablet users. This data will guide optimization efforts for different platforms. 
     *
     *
     * Related guide: <a href="https://docs.fastpix.io/docs/metrics-overview">Understand data definitions</a>
     *
     *
     * @param  Operations\ListBreakdownValuesRequest  $request
     * @return Operations\ListBreakdownValuesResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function listBreakdownValues(Operations\ListBreakdownValuesRequest $request, ?Options $options = null): Operations\ListBreakdownValuesResponse
    {
        $retryConfig = null;
        if ($options) {
            $retryConfig = $options->retryConfig;
        }
        if ($retryConfig === null && $this->sdkConfiguration->retryConfig) {
            $retryConfig = $this->sdkConfiguration->retryConfig;
        } else {
            $retryConfig = new Retry\RetryConfigBackoff(
                initialIntervalMs: 1000,
                maxIntervalMs: 10000,
                exponent: 1.5,
                maxElapsedTimeMs: 3600000,
                retryConnectionErrors: true,
            );
        }
        $retryCodes = null;
        if ($options) {
            $retryCodes = $options->retryCodes;
        }
        if ($retryCodes === null) {
            $retryCodes = [
                '408',
                '429',
                '500',
                '502',
                '503',
                '504',
            ];
        }
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/data/metrics/{metricId}/breakdown', Operations\ListBreakdownValuesRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];

        $qp = Utils\Utils::getQueryParams(Operations\ListBreakdownValuesRequest::class, $request, $urlOverride);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'list_breakdown_values', [], $this->sdkConfiguration->securitySource);
        $httpRequest = $this->sdkConfiguration->hooks->beforeRequest(new Hooks\BeforeRequestContext($hookContext), $httpRequest);
        $httpOptions['query'] = Utils\QueryParameters::standardizeQueryParams($httpRequest, $qp);
        $httpOptions = Utils\Utils::convertHeadersToOptions($httpRequest, $httpOptions);
        $httpRequest = Utils\Utils::removeHeaders($httpRequest);
        try {
            $httpResponse = RetryUtils::retryWrapper(fn () => $this->sdkConfiguration->client->send($httpRequest, $httpOptions), $retryConfig, $retryCodes);
        } catch (\GuzzleHttp\Exception\GuzzleException $error) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), null, $error);
            $httpResponse = $res;
        }
        $contentType = $httpResponse->getHeader('Content-Type')[0] ?? '';

        $statusCode = $httpResponse->getStatusCode();
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '404', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Operations\ListBreakdownValuesResponseBody', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\ListBreakdownValuesResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    object: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPermissionException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['404'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\ViewNotFoundException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['422'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\ValidationErrorResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['4XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['5XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } else {
            throw new \FastPix\Sdk\Models\Errors\APIException('Unknown status code received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        }
    }

    /**
     * List comparison values
     *
     * This endpoint allows you to compare multiple metrics across specified dimensions. You can specify the metrics you want to compare in the query parameters, and the response will include the relevant metrics for the specified dimensions. 
     *
     * #### How it works 
     *
     *   1. Before making a request to this endpoint, call the <a href="https://docs.fastpix.io/reference/list_dimensions">list dimensions</a> endpoint to obtain all available dimensions that can be used for comparison. 
     *
     *   2. Make a `GET` request to this endpoint with the desired metrics specified in the query parameters. 
     *
     *   3. Receive a response containing the comparison values for the specified metrics across the selected dimensions. 
     *
     *
     *   Related guide: <a href="https://docs.fastpix.io/docs/understand-dashboard-ui#compare-metrics">Compare metrics in dashboard</a>
     *
     *
     * @param  Operations\ListComparisonValuesTimespan  $timespan
     * @param  ?string  $filterby
     * @param  ?Operations\ListComparisonValuesDimension  $dimension
     * @param  ?string  $value
     * @return Operations\ListComparisonValuesResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function listComparisonValues(Operations\ListComparisonValuesTimespan $timespan, ?string $filterby = null, ?Operations\ListComparisonValuesDimension $dimension = null, ?string $value = null, ?Options $options = null): Operations\ListComparisonValuesResponse
    {
        $retryConfig = null;
        if ($options) {
            $retryConfig = $options->retryConfig;
        }
        if ($retryConfig === null && $this->sdkConfiguration->retryConfig) {
            $retryConfig = $this->sdkConfiguration->retryConfig;
        } else {
            $retryConfig = new Retry\RetryConfigBackoff(
                initialIntervalMs: 1000,
                maxIntervalMs: 10000,
                exponent: 1.5,
                maxElapsedTimeMs: 3600000,
                retryConnectionErrors: true,
            );
        }
        $retryCodes = null;
        if ($options) {
            $retryCodes = $options->retryCodes;
        }
        if ($retryCodes === null) {
            $retryCodes = [
                '408',
                '429',
                '500',
                '502',
                '503',
                '504',
            ];
        }
        $request = new Operations\ListComparisonValuesRequest(
            timespan: $timespan,
            filterby: $filterby,
            dimension: $dimension,
            value: $value,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/data/metrics/comparison');
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];

        $qp = Utils\Utils::getQueryParams(Operations\ListComparisonValuesRequest::class, $request, $urlOverride);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'list_comparison_values', [], $this->sdkConfiguration->securitySource);
        $httpRequest = $this->sdkConfiguration->hooks->beforeRequest(new Hooks\BeforeRequestContext($hookContext), $httpRequest);
        $httpOptions['query'] = Utils\QueryParameters::standardizeQueryParams($httpRequest, $qp);
        $httpOptions = Utils\Utils::convertHeadersToOptions($httpRequest, $httpOptions);
        $httpRequest = Utils\Utils::removeHeaders($httpRequest);
        try {
            $httpResponse = RetryUtils::retryWrapper(fn () => $this->sdkConfiguration->client->send($httpRequest, $httpOptions), $retryConfig, $retryCodes);
        } catch (\GuzzleHttp\Exception\GuzzleException $error) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), null, $error);
            $httpResponse = $res;
        }
        $contentType = $httpResponse->getHeader('Content-Type')[0] ?? '';

        $statusCode = $httpResponse->getStatusCode();
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '404', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Operations\ListComparisonValuesResponseBody', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\ListComparisonValuesResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    object: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPermissionException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['404'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\ViewNotFoundException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['422'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\ValidationErrorResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['4XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['5XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } else {
            throw new \FastPix\Sdk\Models\Errors\APIException('Unknown status code received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        }
    }

    /**
     * List overall values
     *
     * Retrieves overall values for a specified metric, providing summary statistics that help you understand the performance of your content. The response includes key metrics such as `totalWatchTime`, `uniqueViews`, `totalPlayTime` and `totalViews`. 
     *
     * #### How it works
     *
     *   1. Before using this endpoint, you can call the <a href="https://docs.fastpix.io/reference/list_dimensions">list dimensions</a> endpoint to retrieve all available dimensions that can be used in your query. 
     *
     *   2. Make a `GET` request to this endpoint with the required `metricId` and other query parameters. 
     *
     *   3. Receive a response containing the overall values for the specified metric, which may vary based on the applied filters. 
     *
     *
     *
     *
     *
     *
     * #### Key fields in response
     *
     *
     *   * **value:** The specific metric value calculated based on the applied filters. 
     *   * **totalWatchTime:** Total time watched across all views, represented in milliseconds. 
     *   * **uniqueViews:** The count of unique viewers who interacted with the content. 
     *   * **totalViews:** The total number of views recorded. 
     *   * **totalPlayTime:** Total time spent playing the video, represented in milliseconds. 
     *   * **globalValue:** A global metric value that reflects the overall performance of the specified metric across the entire dataset for the given timespan. This value is not affected by specific filters. 
     *
     *
     *   Related guide: <a href="https://docs.fastpix.io/docs/metrics-overview">Understand data definitions</a>
     *
     *
     * @param  Operations\ListOverallValuesMetricId  $metricId
     * @param  Operations\ListOverallValuesTimespan  $timespan
     * @param  ?string  $measurement
     * @param  ?string  $filterby
     * @return Operations\ListOverallValuesResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function listOverallValues(Operations\ListOverallValuesMetricId $metricId, Operations\ListOverallValuesTimespan $timespan, ?string $measurement = null, ?string $filterby = null, ?Options $options = null): Operations\ListOverallValuesResponse
    {
        $retryConfig = null;
        if ($options) {
            $retryConfig = $options->retryConfig;
        }
        if ($retryConfig === null && $this->sdkConfiguration->retryConfig) {
            $retryConfig = $this->sdkConfiguration->retryConfig;
        } else {
            $retryConfig = new Retry\RetryConfigBackoff(
                initialIntervalMs: 1000,
                maxIntervalMs: 10000,
                exponent: 1.5,
                maxElapsedTimeMs: 3600000,
                retryConnectionErrors: true,
            );
        }
        $retryCodes = null;
        if ($options) {
            $retryCodes = $options->retryCodes;
        }
        if ($retryCodes === null) {
            $retryCodes = [
                '408',
                '429',
                '500',
                '502',
                '503',
                '504',
            ];
        }
        $request = new Operations\ListOverallValuesRequest(
            metricId: $metricId,
            timespan: $timespan,
            measurement: $measurement,
            filterby: $filterby,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/data/metrics/{metricId}/overall', Operations\ListOverallValuesRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];

        $qp = Utils\Utils::getQueryParams(Operations\ListOverallValuesRequest::class, $request, $urlOverride);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'list_overall_values', [], $this->sdkConfiguration->securitySource);
        $httpRequest = $this->sdkConfiguration->hooks->beforeRequest(new Hooks\BeforeRequestContext($hookContext), $httpRequest);
        $httpOptions['query'] = Utils\QueryParameters::standardizeQueryParams($httpRequest, $qp);
        $httpOptions = Utils\Utils::convertHeadersToOptions($httpRequest, $httpOptions);
        $httpRequest = Utils\Utils::removeHeaders($httpRequest);
        try {
            $httpResponse = RetryUtils::retryWrapper(fn () => $this->sdkConfiguration->client->send($httpRequest, $httpOptions), $retryConfig, $retryCodes);
        } catch (\GuzzleHttp\Exception\GuzzleException $error) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), null, $error);
            $httpResponse = $res;
        }
        $contentType = $httpResponse->getHeader('Content-Type')[0] ?? '';

        $statusCode = $httpResponse->getStatusCode();
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '404', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Operations\ListOverallValuesResponseBody', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\ListOverallValuesResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    object: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPermissionException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['404'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\ViewNotFoundException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['422'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\ValidationErrorResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['4XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['5XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } else {
            throw new \FastPix\Sdk\Models\Errors\APIException('Unknown status code received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        }
    }

}