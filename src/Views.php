<?php



declare(strict_types=1);

namespace FastPix\Sdk;

use FastPix\Sdk\Hooks\HookContext;
use FastPix\Sdk\Models\Operations;
use FastPix\Sdk\Utils\Options;
use FastPix\Sdk\Utils\Retry;
use FastPix\Sdk\Utils\Retry\RetryUtils;
use Speakeasy\Serializer\DeserializationContext;

class Views
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
     * Get concurrent viewers breakdown by dimension
     *
     * Retrieves a real-time breakdown of present concurrent viewers, grouped by a chosen dimension. This endpoint allows you to see how your audience is distributed across different categories like geography, content, or technology, based on activity in the last 30 minutes. 
     *
     * For example, you can see how many viewers are currently watching from the US vs. India, or which video titles are most popular right now.
     *
     * #### How it works
     *
     * 1. Make a `GET` request to this endpoint.
     *
     * 2. Specify the `dimension` you want to group by in the query parameters (e.g., `dimension=country` or `dimension=video_title`). This is the most important parameter as it defines how the data is categorized.
     *
     * 3. Optionally, use the `limit` parameter to control the number of results returned (e.g., `limit=5` to get the top 5 countries).
     *
     * 4. The API analyzes viewer data from the last 30 minutes and aggregates the viewer counts for each unique value within the chosen dimension.
     *
     * 5. Receive a response containing a `data` array, where each object represents a specific group (e.g., a country or a video title) and its corresponding number of `concurrent_viewers`.
     *
     * #### Example
     *
     * Imagine you are running a global streaming platform and have just launched a new original series. You want to see, in real-time, which regions are engaging most with the new content versus your older library content.
     *
     * By calling this endpoint with `dimension=video_title`, you can immediately see a list of your most-watched videos right now and their respective viewer counts. Then, by calling it again with `dimension=country`, you can get a live breakdown of your audience's geographic distribution. This helps you confirm if your marketing efforts in specific countries are paying off instantly and allows you to make data-driven decisions during live events.
     *
     *
     * @param  ?Operations\GetDataViewlistCurrentViewsFilterDimension  $dimension
     * @param  ?int  $limit
     * @return Operations\GetDataViewlistCurrentViewsFilterResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function getDataViewlistCurrentViewsFilter(?Operations\GetDataViewlistCurrentViewsFilterDimension $dimension = null, ?int $limit = null, ?Options $options = null): Operations\GetDataViewlistCurrentViewsFilterResponse
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
        $request = new Operations\GetDataViewlistCurrentViewsFilterRequest(
            dimension: $dimension,
            limit: $limit,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/data/viewlist/current-views/filter');
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];

        $qp = Utils\Utils::getQueryParams(Operations\GetDataViewlistCurrentViewsFilterRequest::class, $request, $urlOverride);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'get_/data/viewlist/current-views/filter', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['400', '401', '4XX', '500', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Operations\GetDataViewlistCurrentViewsFilterResponseBody', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\GetDataViewlistCurrentViewsFilterResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    object: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['400'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\BadRequestException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
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
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['4XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['500', '5XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } else {
            throw new \FastPix\Sdk\Models\Errors\APIException('Unknown status code received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        }
    }

    /**
     * Get concurrent viewers timeseries
     *
     * Retrieves a time series of the number of concurrent viewers, providing a real-time snapshot of audience activity over the last 30 minutes. This endpoint is essential for monitoring live events, gauging audience reaction to new content releases, or understanding immediate engagement trends.
     *
     * #### How it works
     *
     *   1. Make a simple `GET` request to this endpoint. No query parameters are needed. 
     *
     *   2. The API automatically gathers data for the **last 30 minutes**, calculating the number of concurrent viewers at regular intervals within that window.
     *
     *   3. Receive a response containing a `data` array, where each object represents a specific point in time.
     *
     *   4. Each object in the array includes the `intervalTime` (the timestamp of the measurement) and `numberOfViews` (the count of concurrent viewers at that instant), allowing you to easily plot viewer activity over time.
     *
     * #### Example
     *
     * Imagine you are streaming a major live event, such as a product launch, a sports game, or a webinar. You need to monitor audience engagement in real-time to see if viewership is increasing, decreasing, or holding steady.
     *
     * By calling this endpoint periodically (e.g., every minute), you can plot a live graph of your viewership. This allows you to identify peak moments of interest, see the immediate impact of social media promotions, or detect potential technical issues if there's a sudden, unexpected drop in viewers
     *
     *
     * @return Operations\GetDataViewlistCurrentViewsGetTimeseriesViewsResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function getDataViewlistCurrentViewsGetTimeseriesViews(?Options $options = null): Operations\GetDataViewlistCurrentViewsGetTimeseriesViewsResponse
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
        $url = Utils\Utils::generateUrl($baseUrl, '/data/viewlist/current-views/getTimeseriesViews');
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'get_/data/viewlist/current-views/getTimeseriesViews', [], $this->sdkConfiguration->securitySource);
        $httpRequest = $this->sdkConfiguration->hooks->beforeRequest(new Hooks\BeforeRequestContext($hookContext), $httpRequest);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['400', '401', '4XX', '500', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Operations\GetDataViewlistCurrentViewsGetTimeseriesViewsResponseBody', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\GetDataViewlistCurrentViewsGetTimeseriesViewsResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    object: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['400'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\BadRequestException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
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
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['4XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['500', '5XX'])) {
            throw new \FastPix\Sdk\Models\Errors\APIException('API error occurred', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        } else {
            throw new \FastPix\Sdk\Models\Errors\APIException('Unknown status code received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
        }
    }

    /**
     * Get details of video view
     *
     * Allows you to retrieve detailed information about a specific video view using its unique `viewId`. This is useful for getting insights into individual viewer interactions with your video content. This detailed information is valuable for enhancing user experience and improving engagement with your video assets. 
     *
     * Make a `GET` request to this endpoint and you will receive a response containing detailed information about the specified video view, including metrics and attributes related to that view. 
     *
     *
     * #### Example
     *
     * Suppose a developer receives a report of a poor viewing experience for a specific user. By using this endpoint with the user's `viewId`, the developer can retrieve metrics like buffering duration, playback errors, and session length. This data allows the developer to pinpoint issues (such as poor connectivity or a browser-specific problem) and take steps to improve the user experience.
     *
     *
     * Related guide: <a href="https://docs.fastpix.io/page/what-video-data-do-we-capture#/">What Video Data do we capture?</a>
     *
     * @param  string  $viewId
     * @return Operations\GetVideoViewDetailsResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function getVideoViewDetails(string $viewId, ?Options $options = null): Operations\GetVideoViewDetailsResponse
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
        $request = new Operations\GetVideoViewDetailsRequest(
            viewId: $viewId,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/data/viewlist/{viewId}', Operations\GetVideoViewDetailsRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'get_video_view_details', [], $this->sdkConfiguration->securitySource);
        $httpRequest = $this->sdkConfiguration->hooks->beforeRequest(new Hooks\BeforeRequestContext($hookContext), $httpRequest);
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Operations\GetVideoViewDetailsResponseBody', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\GetVideoViewDetailsResponse(
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
     * List by top content
     *
     * Retrieves a list of the top video views that fall within the specified filters and have been completed within a defined timespan. It allows you to identify the most popular content based on viewer interactions. 
     *
     * #### How it works
     *
     *   1. Make a `GET` request to this endpoint with the desired query parameters. 
     *
     *   2. Specify the timespan for which you want to retrieve the top content using the `timespan[]` parameter. 
     *
     *   3. Filter the views based on dimensions such as browser, device, video title, etc., using the `filterby[]` parameter. 
     *
     *   4. `Limit` the results to control the number of top views returned. 
     *
     *   5. Receive a response containing the list of top video views matching the specified criteria.
     *
     *
     *   Related guide: <a href="https://docs.fastpix.io/page/how-to-get-top-performing-content">Get top-performing content</a>
     *
     *
     * @param  Operations\ListByTopContentTimespan  $timespan
     * @param  ?string  $filterby
     * @param  ?int  $limit
     * @return Operations\ListByTopContentResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function listByTopContent(Operations\ListByTopContentTimespan $timespan, ?string $filterby = null, ?int $limit = null, ?Options $options = null): Operations\ListByTopContentResponse
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
        $request = new Operations\ListByTopContentRequest(
            timespan: $timespan,
            filterby: $filterby,
            limit: $limit,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/data/viewlist/top-content');
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];

        $qp = Utils\Utils::getQueryParams(Operations\ListByTopContentRequest::class, $request, $urlOverride);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'list_by_top_content', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['400', '401', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Operations\ListByTopContentResponseBody', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\ListByTopContentResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    object: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['400'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\BadRequestException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
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
     * List video views
     *
     * Retrieves a list of video views that fall within the specified filters and have been completed within a defined timespan. It allows you to analyse viewer interactions with your video content effectively. 
     *
     *
     * #### How it works
     *
     *   1. Make a `GET` request to this endpoint with the desired query parameters. 
     *
     *   2. Specify the timespan for which you want to retrieve the video views using the `timespan[]` parameter. 
     *
     *   3. Filter the views based on dimensions such as browser, device, video title, viewer ID, etc., using the `filterby[]` parameter. Get the dimensions by calling <a href="https://docs.fastpix.io/reference/list_dimensions">list the dimensions</a> endpoint. 
     *
     *   4. Paginate the results using the `limit` and `offset` parameters. 
     *
     *   5. Optionally, filter by `viewerId`, `errorCode`, `orderBy` a specific field, and sort in ascending or descending order. 
     *
     *   6. Receive a response containing the list of video views matching the specified criteria.
     *
     * Each view in the response will include a unique `viewId`. You can use this `viewId` to call the <a href="https://docs.fastpix.io/reference/get_video_view_details">get details of video view</a> endpoint to retrieve more detailed information about that specific view. 
     *
     *
     * #### Example
     *
     * Suppose you're managing a video streaming service and need to analyze how the content performs across different devices and browsers. By calling the List Video Views endpoint with filters like `browser_name` and `device_type`, you can get insights into which platforms are most popular with the audience. This data will help you optimize content for the most-used platforms and troubleshoot any playback issues on less common devices.
     *
     *
     *   Related guide: <a href="https://docs.fastpix.io/docs/audience-metrics">Audience metrics</a>, <a href="https://docs.fastpix.io/docs/understand-dashboard-ui#1-views-dashboard">Views dashboard</a>
     *
     * @param  Operations\ListVideoViewsRequest  $request
     * @return Operations\ListVideoViewsResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function listVideoViews(Operations\ListVideoViewsRequest $request, ?Options $options = null): Operations\ListVideoViewsResponse
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
        $url = Utils\Utils::generateUrl($baseUrl, '/data/viewlist');
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];

        $qp = Utils\Utils::getQueryParams(Operations\ListVideoViewsRequest::class, $request, $urlOverride);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'list_video_views', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['400', '401', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Operations\ListVideoViewsResponseBody', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\ListVideoViewsResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    object: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['400'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\BadRequestException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
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