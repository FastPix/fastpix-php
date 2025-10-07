# InputVideo
(*inputVideo*)

## Overview

### Available Operations

* [createMedia](#createmedia) - Create media from URL
* [directUpload](#directupload) - Upload media from device

## createMedia

This endpoint allows developers or users to create a new video or audio media in FastPix using a publicly accessible URL. FastPix will fetch the media from the provided URL, process it, and store it on the platform for use. 



#### Public URL requirement:


  The provided URL must be publicly accessible and should point to a video stored in one of the following supported formats: .m4v, .ogv, .mpeg, .mov, .3gp, .f4v, .rm, .ts, .wtv, .avi, .mp4, .wmv, .webm, .mts, .vob, .mxf, asf, m2ts 



#### Supported storage types:

The URL can originate from various cloud storage services or content delivery networks (CDNs) such as: 


* **Amazon S3:** URLs from Amazon's Simple Storage Service. 

* **Google Cloud Storage:** URLs from Google Cloud's storage solution. 

* **Azure Blob Storage:** URLs from Microsoft's Azure storage. 

* **Public CDNs:** URLs from public content delivery networks that host video files. 

Upon successful creation, the API returns an `id` that should be retained for future operations related to this media. 

#### How it works


1. Send a POST request to this endpoint with the media URL (typically a video or audio file) and optional media settings. 

2. FastPix uploads the video from the provided URL to its storage. 

3. Receive a response containing the unique id for the newly created media item. 

4. Use the id in subsequent API calls, such as checking the status of the media with the <a href="https://docs.fastpix.io/reference/get-media">Get Media by ID</a> endpoint to determine when the media is ready for playback. 

FastPix uses webhooks to tell your application about things that happen in the background, outside of the API regular request flow. For instance, once the media file is created (but not yet processed or encoded), we'll shoot a `POST` message to the address you give us with the webhook event <a href="https://docs.fastpix.io/docs/media-events#videomediacreated">video.media.created</a>. 


Once processing is done you can look for the events <a href="https://docs.fastpix.io/docs/media-events#/videomediaready">video.media.ready<a/> and <a href="https://docs.fastpix.io/docs/media-events#videomediafailed">video.media.failed</a> to see the status of your new media file.

Related guide: <a href="https://docs.fastpix.io/docs/upload-videos-from-url">Upload videos from URL</a>


### Example Usage

<!-- UsageSnippet language="php" operationID="create-media" method="post" path="/on-demand" -->
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

$request = new Components\CreateMediaRequest(
    inputs: [
        new Components\VideoInput(
            type: 'video',
            url: 'https://static.fastpix.io/sample.mp4',
        ),
    ],
    metadata: [
        'key1' => 'value1',
    ],
    accessPolicy: Components\CreateMediaRequestAccessPolicy::Public,
);

$response = $sdk->inputVideo->createMedia(
    request: $request
);

if ($response->createMediaSuccessResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                      | Type                                                                           | Required                                                                       | Description                                                                    |
| ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ |
| `$request`                                                                     | [Components\CreateMediaRequest](../../Models/Components/CreateMediaRequest.md) | :heavy_check_mark:                                                             | The request object to use for the request.                                     |

### Response

**[?Operations\CreateMediaResponse](../../Models/Operations/CreateMediaResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\BadRequestException        | 400                               | application/json                  |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ForbiddenException         | 403                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## directUpload

This endpoint enables accelerated uploads of large media files directly from your local device to FastPix for processing and storage.

> **PLEASE NOTE**
>
> This version now supports uploads with no file size limitations and offers faster uploads. The previous endpoint (which had a 500MB size limit) is now deprecated. You can find details in the [changelog](https://docs.fastpix.io/changelog/api-update-direct-upload-media-from-device).

#### How it works

1. Send a POST request to this endpoint with optional media settings.  

2. The response includes an `uploadId` and a signed `url` for direct video file upload.

3. Upload your video file to the provided `url` by making `PUT` request. The API accepts the media file from the device and uploads it to the FastPix platform. 

4. Once uploaded, the media undergoes processing and is assigned a unique ID for tracking. Retain this `uploadId` for any future operations related to this upload. 




After uploading, you can use the <a href="https://docs.fastpix.io/reference/get-media">Get Media by ID</a> endpoint to check the status of the uploaded media asset and see if it has transitioned to a `ready` status for playback. 

To notify your application about the status of this API request check for the webhooks for <a href="https://docs.fastpix.io/docs/webhooks-collection#media-related-events">media related events</a>.  


#### Example

A social media platform allows users to upload video content directly from their phones or computers. This endpoint facilitates the upload process. For example, if you are developing a video-sharing app where users can upload short clips from their mobile devices, this endpoint enables them to select a video, upload it to the platform.

Related guide: <a href="https://docs.fastpix.io/docs/upload-videos-directly">Upload videos directly</a>


### Example Usage

<!-- UsageSnippet language="php" operationID="direct-upload-video-media" method="post" path="/on-demand/upload" -->
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

$request = new Components\DirectUploadRequest(
    file: 'base64-encoded-file-content',
    fileName: 'sample.mp4',
    contentType: 'video/mp4',
    metadata: [
        'key1' => 'value1',
    ],
    accessPolicy: 'public',
);

$response = $sdk->inputVideo->directUpload(
    request: $request
);

if ($response->directUploadSuccessResponse !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                            | Type                                                                                                 | Required                                                                                             | Description                                                                                          |
| ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- |
| `$request`                                                                                           | [Components\DirectUploadRequest](../../Models/Components/DirectUploadRequest.md) | :heavy_check_mark:                                                                                   | The request object to use for the request.                                                           |

### Response

**[?Operations\DirectUploadResponse](../../Models/Operations/DirectUploadResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\BadRequestException        | 400                               | application/json                  |
| Errors\InvalidPermissionException | 401                               | application/json                  |
| Errors\ForbiddenException         | 403                               | application/json                  |
| Errors\ValidationErrorResponse    | 422                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |