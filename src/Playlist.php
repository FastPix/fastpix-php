<?php



declare(strict_types=1);

namespace FastPix\Sdk;

use FastPix\Sdk\Hooks\HookContext;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;
use FastPix\Sdk\Utils\Options;
use FastPix\Sdk\Utils\Retry;
use FastPix\Sdk\Utils\Retry\RetryUtils;
use Speakeasy\Serializer\DeserializationContext;

class Playlist
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
     * Add media to a playlist by ID
     *
     * This endpoint allows you to add one or more media items to an existing playlist. By passing the media ID(s) in the request, the specified media items are appended to the playlist in the order provided.
     * #### How it works
     *
     *  - When a user sends a PATCH request to this endpoint with the `playlistId` as path parameter and a list of media ID(s) in the request body, FastPix adds the specified media items to the playlist and returns the updated playlist details.
     *  
     * #### Example
     * An e-learning platform adds new video tutorials to the "Beginner Python Series" playlist by sending their media IDs in the request. The playlist is updated with the new content, ensuring learners have access to the latest tutorials in sequence.
     *
     * @param  Components\MediaIdsRequest  $mediaIdsRequest
     * @param  string  $playlistId
     * @return Operations\AddMediaToPlaylistResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function addMediaToPlaylist(Components\MediaIdsRequest $mediaIdsRequest, string $playlistId, ?Options $options = null): Operations\AddMediaToPlaylistResponse
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
        $request = new Operations\AddMediaToPlaylistRequest(
            playlistId: $playlistId,
            mediaIdsRequest: $mediaIdsRequest,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/on-demand/playlists/{playlistId}/media', Operations\AddMediaToPlaylistRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];
        $body = Utils\Utils::serializeRequestBody($request, 'mediaIdsRequest', 'json');
        if ($body === null) {
            throw new \Exception('Request body is required');
        }
        $httpOptions = array_merge_recursive($httpOptions, $body);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('PATCH', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'add-media-to-playlist', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '403', '404', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['201'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Components\PlaylistByIdResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\AddMediaToPlaylistResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    playlistByIdResponse: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\UnauthorizedException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['403'])) {
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\NotFoundError', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPlaylistIdResponseException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
     * Change media order in a playlist by ID
     *
     * This endpoint allows you to change the order of media items within a playlist. By passing the complete list of media IDs in the desired sequence, the playlist's play order is updated accordingly.
     * #### How it works
     *
     *  - When a user sends a PUT request to this endpoint with the `playlistId` as path parameter and the reordered list of all media IDs in the request body, FastPix updates the playlist to reflect the new media sequence and returns the updated playlist details.
     *  
     * #### Example
     * An e-learning platform rearranges the "Beginner Python Series" playlist by submitting a reordered list of media IDs. The playlist now follows the new sequence, providing learners with a better structured learning path.
     *
     * @param  Components\MediaIdsRequest  $mediaIdsRequest
     * @param  string  $playlistId
     * @return Operations\ChangeMediaOrderInPlaylistResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function changeMediaOrderInPlaylist(Components\MediaIdsRequest $mediaIdsRequest, string $playlistId, ?Options $options = null): Operations\ChangeMediaOrderInPlaylistResponse
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
        $request = new Operations\ChangeMediaOrderInPlaylistRequest(
            playlistId: $playlistId,
            mediaIdsRequest: $mediaIdsRequest,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/on-demand/playlists/{playlistId}/media', Operations\ChangeMediaOrderInPlaylistRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];
        $body = Utils\Utils::serializeRequestBody($request, 'mediaIdsRequest', 'json');
        if ($body === null) {
            throw new \Exception('Request body is required');
        }
        $httpOptions = array_merge_recursive($httpOptions, $body);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('PUT', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'change-media-order-in-playlist', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '403', '404', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Components\PlaylistByIdResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\ChangeMediaOrderInPlaylistResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    playlistByIdResponse: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\UnauthorizedException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['403'])) {
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\NotFoundError', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPlaylistIdResponseException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
     * Create a new playlist
     *
     * This endpoint creates a new playlist within a specified workspace. A playlist acts as a container for organizing media items either manually or based on filters and metadata. <br> <br>
     * ### Playlists can be created in two modes
     * - **Manual:** An empty playlist is created without any initial media items. It's intended for manual curation, where items can be added later in a user-defined sequence.
     * - **Smart:** The playlist is auto-populated at creation time based on filters (video creation date range) criteria provided in the request.
     *
     * #### How it works 
     *
     *  - When a user sends a POST request to this endpoint, FastPix creates a playlist and returns a playlist ID, using which items can be added later in a user-defined sequence.
     *  - For a smart playlist, the playlist will be auto-populated based on metadata in the request body.
     *
     *
     * #### Example
     * An e-learning platform creates a new playlist titled "Beginner Python Series" via the API. The response includes a unique playlist ID. The platform then uses this ID to add a series of video tutorials to the playlist in a defined order. The playlist is presented to learners on the frontend as a structured learning path.
     *
     * @param  Components\CreatePlaylistRequest  $request
     * @return Operations\CreateAPlaylistResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function createAPlaylist(Components\CreatePlaylistRequest $request, ?Options $options = null): Operations\CreateAPlaylistResponse
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
        $url = Utils\Utils::generateUrl($baseUrl, '/on-demand/playlists');
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];
        $body = Utils\Utils::serializeRequestBody($request, 'request', 'json');
        if ($body === null) {
            throw new \Exception('Request body is required');
        }
        $httpOptions = array_merge_recursive($httpOptions, $body);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('POST', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'create-a-playlist', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '403', '409', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['201'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Components\PlaylistCreatedResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\CreateAPlaylistResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    playlistCreatedResponse: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\UnauthorizedException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['403'])) {
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
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['409'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\DuplicateReferenceIdErrorResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
     * Delete a playlist by ID
     *
     * This endpoint allows you to delete an existing playlist from the workspace. Once deleted, the playlist and its metadata are permanently removed and cannot be recovered.
     * #### How it works
     *  - When a user sends a DELETE request to this endpoint with the `playlistId`, FastPix removes the specified playlist from the workspace and returns a confirmation of successful deletion.
     *  
     * #### Example
     * An e-learning platform deletes an outdated playlist titled "Old Python Tutorials" by providing its unique playlist ID. The platform receives confirmation that the playlist has been removed, ensuring learners no longer see the obsolete content.
     *
     * @param  string  $playlistId
     * @return Operations\DeleteAPlaylistResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function deleteAPlaylist(string $playlistId, ?Options $options = null): Operations\DeleteAPlaylistResponse
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
        $request = new Operations\DeleteAPlaylistRequest(
            playlistId: $playlistId,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/on-demand/playlists/{playlistId}', Operations\DeleteAPlaylistRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('DELETE', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'delete-a-playlist', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '403', '404', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Components\SuccessResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\DeleteAPlaylistResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    successResponse: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\UnauthorizedException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['403'])) {
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\NotFoundError', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPlaylistIdResponseException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
     * Delete media in a playlist by ID
     *
     * This endpoint allows you to delete one or more media items from an existing playlist. By passing the media ID(s) in the request, the specified media items are removed from the playlist.
     * #### How it works
     *
     *  - When a user sends a DELETE request to this endpoint with the playlist ID as the path parameter and the media ID(s) to be removed in the request body, FastPix deletes the specified media items from the playlist and returns the updated playlist details.
     *  
     * #### Example
     * An e-learning platform removes outdated video tutorials from the "Beginner Python Series" playlist by specifying their media IDs in the request. The playlist is updated to exclude these items, ensuring learners only access relevant content.
     *
     * @param  string  $playlistId
     * @param  ?Components\MediaIdsRequest  $mediaIdsRequest
     * @return Operations\DeleteMediaFromPlaylistResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function deleteMediaFromPlaylist(string $playlistId, ?Components\MediaIdsRequest $mediaIdsRequest = null, ?Options $options = null): Operations\DeleteMediaFromPlaylistResponse
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
        $request = new Operations\DeleteMediaFromPlaylistRequest(
            playlistId: $playlistId,
            mediaIdsRequest: $mediaIdsRequest,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/on-demand/playlists/{playlistId}/media', Operations\DeleteMediaFromPlaylistRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];
        $body = Utils\Utils::serializeRequestBody($request, 'mediaIdsRequest', 'json');
        if ($body !== null) {
            $httpOptions = array_merge_recursive($httpOptions, $body);
        }
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('DELETE', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'delete-media-from-playlist', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '403', '404', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Components\PlaylistByIdResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\DeleteMediaFromPlaylistResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    playlistByIdResponse: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\UnauthorizedException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['403'])) {
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\NotFoundError', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPlaylistIdResponseException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
     * Get all playlists
     *
     * This endpoint retrieves all playlists present within a specified workspace. It allows users to view the collection of playlists that have been created, whether manual or smart, along with their associated metadata.
     * #### How it works
     *
     *  - When a user sends a GET request to this endpoint, FastPix returns a list of all playlists in the workspace, including details such as playlist IDs, titles, creation mode (manual or smart), and other relevant metadata.
     *  
     * #### Example
     *
     *   An e-learning platform requests all playlists within a workspace to display an overview of available learning paths. The response includes multiple playlists like "Beginner Python Series" and "Advanced Java Tutorials," enabling the platform to show users a catalog of curated content collections.
     *
     * @param  ?int  $limit
     * @param  ?int  $offset
     * @return Operations\GetAllPlaylistsResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function getAllPlaylists(?int $limit = null, ?int $offset = null, ?Options $options = null): Operations\GetAllPlaylistsResponse
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
        $request = new Operations\GetAllPlaylistsRequest(
            limit: $limit,
            offset: $offset,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/on-demand/playlists');
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];

        $qp = Utils\Utils::getQueryParams(Operations\GetAllPlaylistsRequest::class, $request, $urlOverride);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'get-all-playlists', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['200'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Components\GetAllPlaylistsResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\GetAllPlaylistsResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    getAllPlaylistsResponse: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\UnauthorizedException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
     * Get a playlist by ID
     *
     * This endpoint retrieves detailed information about a specific playlist using its unique `playlistId`. It provides comprehensive metadata about the playlist, including its title, creation mode (manual or smart), media items along with the metadata of each media in the playlist.
     *
     *  
     * #### Example
     * An e-learning platform requests details for the playlist "Beginner Python Series" by providing its unique `playlistId`. The response includes the playlist's title, creation mode, and the ordered list of video tutorials contained within, enabling the platform to present the full learning path to users.
     *
     * @param  string  $playlistId
     * @return Operations\GetPlaylistByIdResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function getPlaylistById(string $playlistId, ?Options $options = null): Operations\GetPlaylistByIdResponse
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
        $request = new Operations\GetPlaylistByIdRequest(
            playlistId: $playlistId,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/on-demand/playlists/{playlistId}', Operations\GetPlaylistByIdRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('GET', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'get-playlist-by-id', [], $this->sdkConfiguration->securitySource);
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Components\PlaylistByIdResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\GetPlaylistByIdResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    playlistByIdResponse: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\UnauthorizedException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\NotFoundError', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPlaylistIdResponseException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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
     * Update a playlist by ID
     *
     * This endpoint allows you to update the name and description of an existing playlist. It enables modifications to the playlist's metadata without altering the media items or playlist structure.
     * #### How it works
     *
     *  - When a user sends a PUT request to this endpoint with the `playlistId` and updated name and description in the request body, FastPix updates the playlist metadata accordingly and returns the updated playlist details.
     *
     * #### Example
     * An e-learning platform updates the playlist titled "Beginner Python Series" to rename it as "Python Basics" and add a more detailed description. The updated metadata is reflected when retrieving the playlist, helping users better understand the playlist content.
     *
     * @param  Components\UpdatePlaylistRequest  $updatePlaylistRequest
     * @param  string  $playlistId
     * @return Operations\UpdateAPlaylistResponse
     * @throws \FastPix\Sdk\Models\Errors\APIException
     */
    public function updateAPlaylist(Components\UpdatePlaylistRequest $updatePlaylistRequest, string $playlistId, ?Options $options = null): Operations\UpdateAPlaylistResponse
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
        $request = new Operations\UpdateAPlaylistRequest(
            playlistId: $playlistId,
            updatePlaylistRequest: $updatePlaylistRequest,
        );
        $baseUrl = $this->sdkConfiguration->getTemplatedServerUrl();
        $url = Utils\Utils::generateUrl($baseUrl, '/on-demand/playlists/{playlistId}', Operations\UpdateAPlaylistRequest::class, $request);
        $urlOverride = null;
        $httpOptions = ['http_errors' => false];
        $body = Utils\Utils::serializeRequestBody($request, 'updatePlaylistRequest', 'json');
        if ($body === null) {
            throw new \Exception('Request body is required');
        }
        $httpOptions = array_merge_recursive($httpOptions, $body);
        $httpOptions['headers']['Accept'] = 'application/json';
        $httpOptions['headers']['user-agent'] = $this->sdkConfiguration->userAgent;
        $httpRequest = new \GuzzleHttp\Psr7\Request('PUT', $url);
        $hookContext = new HookContext($this->sdkConfiguration, $baseUrl, 'update-a-playlist', [], $this->sdkConfiguration->securitySource);
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
        if (Utils\Utils::matchStatusCodes($statusCode, ['401', '403', '422', '4XX', '5XX'])) {
            $res = $this->sdkConfiguration->hooks->afterError(new Hooks\AfterErrorContext($hookContext), $httpResponse, null);
            $httpResponse = $res;
        }
        if (Utils\Utils::matchStatusCodes($statusCode, ['201'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Components\PlaylistCreatedResponse', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $response = new Operations\UpdateAPlaylistResponse(
                    statusCode: $statusCode,
                    contentType: $contentType,
                    rawResponse: $httpResponse,
                    playlistCreatedResponse: $obj);

                return $response;
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['401'])) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $httpResponse = $this->sdkConfiguration->hooks->afterSuccess(new Hooks\AfterSuccessContext($hookContext), $httpResponse);

                $serializer = Utils\JSON::createSerializer();
                $responseData = (string) $httpResponse->getBody();
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\UnauthorizedException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
                $obj->rawResponse = $httpResponse;
                throw $obj->toException();
            } else {
                throw new \FastPix\Sdk\Models\Errors\APIException('Unknown content type received', $statusCode, $httpResponse->getBody()->getContents(), $httpResponse);
            }
        } elseif (Utils\Utils::matchStatusCodes($statusCode, ['403'])) {
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
                $obj = $serializer->deserialize($responseData, '\FastPix\Sdk\Models\Errors\InvalidPlaylistIdResponseException', 'json', DeserializationContext::create()->setRequireAllRequiredProperties(true));
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