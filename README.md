# FastPix PHP SDK

[![Packagist version](https://img.shields.io/packagist/v/fastpix/sdk)](https://packagist.org/packages/fastpix/sdk)
[![Packagist downloads](https://img.shields.io/packagist/dt/fastpix/sdk)](https://packagist.org/packages/fastpix/sdk)
[![license](https://img.shields.io/packagist/l/fastpix/sdk)](https://github.com/FastPix/fastpix-php/blob/main/LICENSE)
[![PHP version](https://img.shields.io/packagist/php-v/fastpix/sdk)](https://www.php.net/)

A robust, type-safe PHP SDK designed for seamless integration with the FastPix API platform.

The FastPix PHP SDK is a strongly typed PHP client for the FastPix video API. From any PHP application, you can upload and manage videos, run live streams and simulcasts, create and secure playback IDs, manage playlists and signing keys, retrieve video analytics, and use in-video AI features.

**Supported PHP:** 8.2 and later
**Package:** `fastpix/sdk`
**Authentication:** HTTP Basic Authentication
**Dependency management:** Composer

📖 **Docs:** [FastPix PHP SDK](https://fastpix.com/docs/language-sdks/php-sdk) · 🚀 **Free account:** [FastPix Dashboard](https://dashboard.fastpix.com)

---

## Start here

If you are using the FastPix PHP SDK for the first time, follow these steps in order:

1. [Check your PHP version](#1-check-your-php-version)
2. [Check your Composer installation](#2-check-your-composer-installation)
3. [Create a PHP project](#3-create-a-php-project)
4. [Install the FastPix SDK](#4-install-the-fastpix-sdk)
5. [Verify the SDK installation](#5-verify-the-sdk-installation)
6. [Verify that PHP can load the SDK](#6-verify-that-php-can-load-the-sdk)
7. [Configure authentication](#7-configure-authentication)
8. [Verify that your credentials are set](#8-verify-that-your-credentials-are-set)
9. [Initialize the FastPix client](#9-initialize-the-fastpix-client)
10. [Make your first API request](#10-make-your-first-api-request)
11. [Capture the media ID](#11-capture-the-media-id)
12. [Verify the integration](#12-verify-the-integration)

Do not skip the verification steps.
If installation, dependency loading, authentication, client initialization, or the first API request fails, troubleshoot that problem before continuing.

---

## Before you begin

Make sure you have:

- PHP 8.2 or later.
- Composer.
- Internet access.
- A FastPix account.
- A FastPix Access Token.
- A FastPix Secret Key.

FastPix uses HTTP Basic Authentication:

| SDK value | FastPix credential |
|---|---|
| `username` | Access Token |
| `password` | Secret Key |

You can obtain your credentials from the FastPix Dashboard.
Follow the [Authentication with Basic Auth](https://fastpix.com/docs/getting-started/activate-your-account) guide for information about obtaining your credentials.

> **Security:** Never commit your Access Token or Secret Key to source control. Use environment variables or a secure credential-management system.

---

## 1. Check your PHP version

Run:

```bash
php --version
```

Output is similar to:

```text
PHP 8.5.10 (cli) ...
```

The FastPix PHP SDK supports PHP 8.2 and later.
If your PHP version is earlier than 8.2, install a supported PHP version before continuing.

You can also check the exact PHP version:

```bash
php -r 'echo PHP_VERSION, PHP_EOL;'
```

Expected output is similar to:

```text
8.5.10
```

---

## 2. Check your Composer installation

The FastPix PHP SDK uses Composer to install and manage dependencies.

Run:

```bash
composer --version
```

Expected output is similar to:

```text
Composer version 2.x.x
```

You can also verify where Composer is installed:

```bash
which composer
```

On Windows PowerShell:

```powershell
Get-Command composer
```

If you see:

```text
zsh: command not found: composer
```

Composer is not available in your shell.
Install Composer before continuing.

Do not continue until this command works:

```bash
composer --version
```

---

## 3. Create a PHP project

Create a new directory for your FastPix application:

```bash
mkdir fastpix-php-demo
cd fastpix-php-demo
```

Initialize a Composer project:

```bash
composer init
```

Composer prompts you for project information.
For a simple SDK test application, you can accept the default values.

When Composer asks:

```text
Package name (<vendor>/<name>) [your-name/fastpix-php-demo]:
```

Press **Enter** to accept the suggested package name.

> **Do not enter `ls` at the package-name prompt.** `ls` is a shell command, not a valid Composer package name.

When Composer asks whether you want to define dependencies interactively, you can select `no`. The FastPix SDK will be added explicitly in the next step.

After initialization, your project should contain:

```text
fastpix-php-demo/
└── composer.json
```

---

## 4. Install the FastPix SDK

Install the FastPix PHP SDK with Composer:

```bash
composer require fastpix/sdk
```

Composer installs the SDK and its dependencies.

After installation, your project should contain:

```text
fastpix-php-demo/
├── composer.json
├── composer.lock
└── vendor/
```

The `vendor/` directory contains the installed SDK and Composer's autoloader.

---

## 5. Verify the SDK installation

Before writing application code, verify that Composer installed the FastPix SDK.

Run:

```bash
composer show fastpix/sdk
```

The output should identify the FastPix SDK package and installed version.

You can also search all installed packages:

### macOS and Linux

```bash
composer show | grep fastpix
```

### Windows PowerShell

```powershell
composer show | Select-String fastpix
```

If `fastpix/sdk` is not listed, do not continue.

Run:

```bash
composer install
```

Then verify again:

```bash
composer show fastpix/sdk
```

---

## 6. Verify that PHP can load the SDK

Before configuring authentication or making an API request, verify that PHP can load the SDK.

Create a file named `verify.php`:

```php
<?php
require 'vendor/autoload.php';

use FastPix\Sdk;

echo "FastPix SDK loaded successfully" . PHP_EOL;
```

> **Note:** This example intentionally does not use `declare(strict_types=1);`. The declaration is not required for this SDK verification example and can introduce an unnecessary PHP parsing issue if any output or whitespace appears before the PHP opening tag.

Run:

```bash
php verify.php
```

Expected output:

```text
FastPix SDK loaded successfully
```

This verifies that:

- PHP can execute the application.
- Composer's autoloader is available.
- The FastPix SDK can be loaded.

If this command fails, do not continue to API requests.
Check:

- PHP 8.2 or later is installed.
- `composer require fastpix/sdk` completed successfully.
- `vendor/autoload.php` exists.
- `fastpix/sdk` is listed by `composer show`.
- You are running the command from the `fastpix-php-demo` directory.

---

## 7. Configure authentication

FastPix uses HTTP Basic Authentication.

The SDK expects:

```text
username → Access Token
password → Secret Key
```

For local development, configure these values as environment variables.

### macOS and Linux

```bash
export FASTPIX_USERNAME="<YOUR_ACCESS_TOKEN>"
export FASTPIX_PASSWORD="<YOUR_SECRET_KEY>"
```

### Windows PowerShell

```powershell
$env:FASTPIX_USERNAME="<YOUR_ACCESS_TOKEN>"
$env:FASTPIX_PASSWORD="<YOUR_SECRET_KEY>"
```

The SDK maps the environment variables as follows:

```text
FASTPIX_USERNAME → Access Token
FASTPIX_PASSWORD → Secret Key
```

---

## 8. Verify that your credentials are set

Do not print the actual credential values.

### macOS and Linux

Run:

```bash
if [ -n "$FASTPIX_USERNAME" ]; then
  echo "Access Token: set"
else
  echo "Access Token: missing"
fi

if [ -n "$FASTPIX_PASSWORD" ]; then
  echo "Secret Key: set"
else
  echo "Secret Key: missing"
fi
```

Expected output:

```text
Access Token: set
Secret Key: set
```

If either value is reported as `missing`, set the corresponding environment variable before continuing.

### Windows PowerShell

Run:

```powershell
if ($env:FASTPIX_USERNAME) {
    Write-Output "Access Token: set"
} else {
    Write-Output "Access Token: missing"
}

if ($env:FASTPIX_PASSWORD) {
    Write-Output "Secret Key: set"
} else {
    Write-Output "Secret Key: missing"
}
```

Expected output:

```text
Access Token: set
Secret Key: set
```

### Security

Never:

- Commit credentials to Git.
- Put credentials directly into source code.
- Include credentials in screenshots.
- Print credentials in logs.
- Include credentials in bug reports or support requests.
- Log HTTP authentication headers in production.

Use environment variables or a secure credential-management system.

---

## 9. Initialize the FastPix client

Create or replace `main.php` with:

```php
<?php
require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$username = getenv('FASTPIX_USERNAME');
$password = getenv('FASTPIX_PASSWORD');

if ($username === false || $username === '') {
    throw new RuntimeException('FASTPIX_USERNAME is not set');
}
if ($password === false || $password === '') {
    throw new RuntimeException('FASTPIX_PASSWORD is not set');
}

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(
        new Components\Security(
            username: $username,
            password: $password,
        )
    )
    ->build();

echo "FastPix client initialized" . PHP_EOL;
```

Run:

```bash
php main.php
```

Expected output:

```text
FastPix client initialized
```

### What this code does

`Sdk\Fastpixsdk::builder()` creates the FastPix SDK client.
`setSecurity()` configures the credentials used for HTTP Basic Authentication.
`build()` creates the configured SDK client.

Initializing the client does **not** make an API request.
An API request occurs when you call an SDK operation such as:

```php
$sdk->inputVideo->createMedia(...)
```

---

## 10. Make your first API request

The easiest way to verify the complete PHP SDK integration is to create media from a publicly accessible video URL.

FastPix provides a sample video URL:

```text
https://static.fastpix.com/fp-sample-video.mp4
```

The PHP SDK exposes media creation through:

```php
$sdk->inputVideo->createMedia()
```

Replace the contents of `main.php` with:

```php
<?php
require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$username = getenv('FASTPIX_USERNAME');
$password = getenv('FASTPIX_PASSWORD');

if ($username === false || $username === '') {
    throw new RuntimeException('FASTPIX_USERNAME is not set');
}
if ($password === false || $password === '') {
    throw new RuntimeException('FASTPIX_PASSWORD is not set');
}

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(
        new Components\Security(
            username: $username,
            password: $password,
        )
    )
    ->build();

try {
    $request = new Components\CreateMediaRequest(
        inputs: [
            new Components\PullVideoInput(
                url: 'https://static.fastpix.com/fp-sample-video.mp4',
            ),
        ],
        metadata: [
            'source' => 'fastpix-php-demo',
        ],
    );

    $response = $sdk->inputVideo->createMedia(
        request: $request,
    );

    if ($response->statusCode >= 200 && $response->statusCode < 300) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo json_encode(
            $decoded ?? $rawBody,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
        ) . PHP_EOL;
    } else {
        $errorPayload = $response->defaultError
            ?? $response->error
            ?? null;
        if ($errorPayload !== null) {
            echo json_encode(
                $errorPayload,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
            ) . PHP_EOL;
        } else {
            echo json_encode(
                ['message' => 'No response data'],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
            ) . PHP_EOL;
        }
        exit(1);
    }
} catch (\Exception $e) {
    echo json_encode(
        ['error' => $e->getMessage()],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
    ) . PHP_EOL;
    exit(1);
}
```

Run:

```bash
php main.php
```

A successful request returns information about the newly created media asset.
The response contains information similar to:

```json
{
  "success": true,
  "data": {
    "id": "..."
  }
}
```

The exact response fields depend on the API response and installed SDK version.

---

## 11. Capture the media ID

The create-media response contains the unique ID assigned to the media asset.

The media ID is available at:

```text
data.id
```

For example:

```json
{
  "success": true,
  "data": {
    "id": "12345678-1234-1234-1234-123456789abc"
  }
}
```

The value of:

```text
data.id
```

is the `media_id`.

Save the value for subsequent API operations:

```text
MEDIA_ID=<value returned in data.id>
```

Do not confuse a `media_id` with a `playback_id`.
They identify different resources and are used for different operations.

---

## 12. Verify the integration

At this point, you have verified the initial FastPix PHP SDK integration.

A successful create-media request confirms that:

- PHP is installed and supported.
- Composer is installed.
- The PHP project is initialized.
- The FastPix PHP SDK is installed.
- Composer dependencies are available.
- PHP can load the FastPix SDK.
- Your FastPix credentials are configured.
- The FastPix client can be initialized.
- Your application can authenticate with the FastPix API.
- Your application can create a media asset.
- FastPix returns a media ID.

The completed workflow is:

<Image alt="FastPix PHP SDK workflow: a PHP application uses Composer to install the FastPix PHP SDK, which authenticates to the FastPix API over HTTP Basic Auth, creates a media asset, and receives a media ID (data.id)." border={false} src="https://static.fastpix.com/php-media-workflow.png" />

At this point, the initial SDK integration is complete.

<br />

## Available Resources and Operations

Comprehensive PHP SDK for FastPix platform integration with full API coverage.

### Media API

Upload, manage, and transform video content with comprehensive media management capabilities.

For detailed documentation, see [FastPix Video on Demand Overview](https://fastpix.com/docs/video-on-demand-api/overview).

#### Input Video
- [Create from URL](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/inputvideo/README.md#createmedia) - Upload video content from external URL
- [Upload from Device](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/inputvideo/README.md#directuploadvideomedia) - Upload video files directly from device

#### Manage Videos
- [List All Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#listmedia) - Retrieve complete list of all media files
- [Get Media by ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#getmedia) - Get detailed information for specific media
- [Update Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#updatedmedia) - Modify media metadata and settings
- [Delete Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#deletemedia) - Remove media files from library
- [Cancel Upload](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#cancelupload) - Stop ongoing media upload process
- [Get Input Info](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#retrievemediainputinfo) - Retrieve detailed input information
- [List Uploads](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#listuploads) - Get all available upload URLs
- [Get Media Summary](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#getmediasummary) - Get summary for a media
- [List Live Clips](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#listliveclips) - List live clips for a livestream

#### Playback
- [Create Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#createmediaplaybackid) - Generate secure playback identifier
- [List Playback IDs](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#listplaybackids) - List all playback IDs for a media
- [Get Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#getplaybackid) - Retrieve playback configuration details
- [Delete Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#deletemediaplaybackid) - Remove playback access
- [Update Domain Restrictions](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#updatedomainrestrictions) - Update domain allow/deny list for playback
- [Update User Agent Restrictions](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playback/README.md#updateuseragentrestrictions) - Update user-agent allow/deny list for playback

#### Playlist
- [Create Playlist](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#createaplaylist) - Create new video playlist
- [List Playlists](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#getallplaylists) - Get all available playlists
- [Get Playlist](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#getplaylistbyid) - Retrieve specific playlist details
- [Update Playlist](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#updateaplaylist) - Modify playlist settings and metadata
- [Delete Playlist](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#deleteaplaylist) - Remove playlist from library
- [Add Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#addmediatoplaylist) - Add media items to playlist
- [Reorder Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#changemediaorderinplaylist) - Change order of media in playlist
- [Remove Media](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/playlist/README.md#deletemediafromplaylist) - Remove media from playlist

#### Signing Keys
- [Create Key](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/signingkeys/README.md#createsigningkey) - Generate new signing key pair
- [List Keys](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/signingkeys/README.md#listsigningkeys) - Get all available signing keys
- [Delete Key](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/signingkeys/README.md#deletesigningkey) - Remove signing key from system
- [Get Key](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/signingkeys/README.md#getsigningkeybyid) - Retrieve specific signing key details

#### DRM Configurations
- [List DRM Configs](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/drmconfigurations/README.md#getdrmconfiguration) - Get all DRM configuration options
- [Get DRM Config](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/drmconfigurations/README.md#getdrmconfigurationbyid) - Retrieve specific DRM configuration

### Live API

Stream, manage, and transform live video content with real-time broadcasting capabilities.

For detailed documentation, see [FastPix Live Stream Overview](https://fastpix.com/docs/live-stream-api/overview).

#### Start Live Stream
- [Create Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/startlivestream/README.md#createnewstream) - Initialize new live streaming session with DVR mode support

#### Manage Live Stream
- [List Streams](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#getallstreams) - Retrieve all active live streams
- [Get Viewer Count](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#getlivestreamviewercountbyid) - Get real-time viewer statistics
- [Get Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#getlivestreambyid) - Retrieve detailed stream information
- [Delete Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#deletelivestream) - Terminate and remove live stream
- [Update Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#updatelivestream) - Modify stream settings and configuration
- [Enable Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#enablelivestream) - Activate live streaming
- [Disable Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#disablelivestream) - Pause live streaming
- [Complete Stream](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managelivestream/README.md#completelivestream) - Finalize and archive stream

#### Live Playback
- [Create Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/liveplayback/README.md#createplaybackidofstream) - Generate secure live playback access
- [Delete Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/liveplayback/README.md#deleteplaybackidofstream) - Revoke live playback access
- [Get Playback ID](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/liveplayback/README.md#getlivestreamplaybackid) - Retrieve live playback configuration

#### Simulcast Stream
- [Create Simulcast](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/simulcaststream/README.md#createsimulcastofstream) - Set up multi-platform streaming
- [Delete Simulcast](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/simulcaststream/README.md#deletesimulcastofstream) - Remove simulcast configuration
- [Get Simulcast](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/simulcaststream/README.md#getspecificsimulcastofstream) - Retrieve simulcast settings
- [Update Simulcast](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/simulcaststream/README.md#updatespecificsimulcastofstream) - Modify simulcast parameters

### Video Data API

Monitor video performance and quality with comprehensive analytics and real-time metrics.

For detailed documentation, see [FastPix Video Data Overview](https://fastpix.com/docs/video-data-api/overview).

#### Metrics
- [List Breakdown Values](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/metrics/README.md#listbreakdownvalues) - Get detailed breakdown of metrics by dimension
- [List Overall Values](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/metrics/README.md#listoverallvalues) - Get aggregated metric values across all content
- [Get Timeseries Data](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/metrics/README.md#gettimeseriesdata) - Retrieve time-based metric trends and patterns

#### Views
- [List Video Views](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/views/README.md#listvideoviews) - Get comprehensive list of video viewing sessions
- [Get View Details](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/views/README.md#getvideoviewdetails) - Retrieve detailed information about specific video views
- [List Top Content](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/views/README.md#listbytopcontent) - Find your most popular and engaging content
- [Get Concurrent Viewers](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/metrics/README.md#listcomparisonvalues) - Monitor real-time viewer counts over time

#### Dimensions
- [List Dimensions](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/dimensions/README.md#listdimensions) - Get available data dimensions for filtering and analysis
- [List Filter Values](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/dimensions/README.md#listfiltervaluesfordimension) - Get specific values for a particular dimension

#### Errors
- [List Errors](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/errors/README.md#listerrors) - List playback errors for diagnostics and monitoring

### Transformations

Transform and enhance your video content with powerful AI and editing capabilities.

#### In-Video AI Features

Enhance video content with AI-powered features including moderation, summarization, and intelligent categorization.

- [Update Summary](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/invideoaifeatures/README.md#updatemediasummary) - Create AI-generated video summaries
- [Create Chapters](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/invideoaifeatures/README.md#updatemediachapters) - Automatically generate video chapter markers
- [Extract Entities](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/invideoaifeatures/README.md#updatemedianamedentities) - Identify and extract named entities from content
- [Enable Moderation](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/invideoaifeatures/README.md#updatemediamoderation) - Activate content moderation and safety checks

#### Media Clips
- [Get Media Clips](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#getmediaclips) - Retrieve all clips associated with a source media

#### Subtitles
- [Generate Subtitles](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#generatesubtitletrack) - Create automatic subtitles for media

#### Media Tracks
- [Add Track](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#addmediatrack) - Add audio or subtitle tracks to media
- [Update Track](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#updatemediatrack) - Modify existing audio or subtitle tracks
- [Delete Track](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#deletemediatrack) - Remove audio or subtitle tracks

#### Access Control
- [Update Source Access](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#updatedsourceaccess) - Control access permissions for media source

#### Format Support
- [Update MP4 Support](https://github.com/FastPix/fastpix-php/blob/feature/fixed-missing-parameters/docs/sdks/managevideos/README.md#updatedmp4support) - Configure MP4 download capabilities

## Error Handling

All operations return a response object or throw an exception. By default, an API error will raise an `Errors\APIException` (or operation-specific error types).

| Property       | Type                                    | Description           |
|----------------|-----------------------------------------|-----------------------|
| `$message`     | *string*                                | The error message     |
| `$statusCode`  | *int*                                   | The HTTP status code  |
| `$rawResponse` | *?\Psr\Http\Message\ResponseInterface*  | The raw HTTP response |
| `$body`        | *string*                                | The response content  |

### Example

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

try {
    $request = new Components\CreateMediaRequest(
        inputs: [new Components\PullVideoInput(url: 'https://static.fastpix.com/fp-sample-video.mp4')],
        metadata: ['key1' => 'value1'],
    );

    $response = $sdk->inputVideo->createMedia(request: $request);

    if ($response->createMediaSuccessResponse !== null) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
} catch (\FastPix\Sdk\Models\Errors\APIException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Status: " . $e->statusCode . "\n";
    echo "Body: " . $e->body . "\n";
}
```

Refer to the *Errors* tables in each operation’s SDK doc for possible exception types.

## Server Selection

### Override Server URL Per-Client

Override the default server by passing a URL when building the SDK:

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\Fastpixsdk::builder()
    ->setServerUrl('https://api.fastpix.com/v1/')
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();
```

## FAQ

**How do I install the FastPix PHP SDK?**
It is a Composer package - add `fastpix/sdk` to your `composer.json` and run `composer update` (or `composer require fastpix/sdk`). See [Install the FastPix SDK](#4-install-the-fastpix-sdk).

**How do I authenticate the SDK?**
FastPix uses Basic Auth: pass your access token as the `username` and your secret key as the `password` in `Components\Security` when building the client. See [Initialize the FastPix client](#9-initialize-the-fastpix-client).

**How do I upload a video in PHP?**
Create media from a URL or a direct upload through the input-video resource on the built `$sdk`. See [Make your first API request](#10-make-your-first-api-request) and [Available Resources and Operations](#available-resources-and-operations).

**How do I start a live stream?**
Use the Live API resources to create and manage streams, simulcasts, and live playback IDs. See [Available Resources and Operations](#available-resources-and-operations).

**How do I create a secure playback ID?**
Generate playback IDs and manage signing keys and DRM configurations through the Media API resources. See [Available Resources and Operations](#available-resources-and-operations).

**How do I get video analytics and metrics in PHP?**
The Video Data API exposes metrics, views, dimensions, and errors for quality-of-experience monitoring. See [Available Resources and Operations](#available-resources-and-operations).

**How do I handle API errors?**
Wrap calls in try/catch; the SDK throws a typed error exposing the message, status code, and response body. See [Error Handling](#error-handling).

**How do I change the API base URL?**
Pass a server URL with `setServerUrl(...)` when building the client. See [Server Selection](#server-selection).

**Which PHP versions are supported?**
PHP 8.2 and above. See [Before you begin](#before-you-begin).

**Is the SDK strongly typed?**
Yes - it is a type-safe client generated from the FastPix API specification. See [Development](#development).

## Which FastPix SDK should I use?

FastPix publishes a server SDK for every major backend language, each generated from the same API specification:

| Language | Repo | Install |
|---|---|---|
| **PHP** (this repo) | [fastpix-php](https://github.com/FastPix/fastpix-php) | `composer require fastpix/sdk` |
| Python | [fastpix-python](https://github.com/FastPix/fastpix-python) | `pip install fastpix-python` |
| Go | [fastpix-go](https://github.com/FastPix/fastpix-go) | `go get github.com/FastPix/fastpix-go` |
| Java | [fastpix-java](https://github.com/FastPix/fastpix-java) | `io.fastpix:sdk` (Maven/Gradle) |
| C# / .NET | [fastpix-sdk-csharp](https://github.com/FastPix/fastpix-sdk-csharp) | `dotnet add package Fastpix` |
| Ruby | [fastpix-ruby](https://github.com/FastPix/fastpix-ruby) | `gem install fastpixapi` |

To upload and play the media these SDKs create, use the FastPix browser libraries: [web-uploads-sdk](https://github.com/FastPix/web-uploads-sdk), [react-web-uploader](https://github.com/FastPix/react-web-uploader), and [web-player-component](https://github.com/FastPix/web-player-component). Browse everything in the [FastPix organization](https://github.com/orgs/FastPix/repositories).

## Development

This PHP SDK is programmatically generated from our API specifications. Any manual modifications to internal files will be overwritten during subsequent generation cycles.

We value community contributions and feedback. Feel free to submit pull requests or open issues with your suggestions, and we'll do our best to include them in future releases.

### Detailed Usage

For comprehensive understanding of each API's functionality, including detailed request and response specifications, parameter descriptions, and additional examples, please refer to the [FastPix API Reference](https://fastpix.com/docs/product-os-api/overview).

The API reference offers complete documentation for all available endpoints and features, enabling developers to integrate and leverage FastPix APIs effectively.

---
