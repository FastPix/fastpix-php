# FastPix PHP SDK examples

Runnable examples for the FastPix PHP SDK. Each file at the top level is
self-contained — grab one, set your credentials, and run it.

## Setup

From the repo root, install dependencies once:

```bash
composer install
```

Then set your credentials. From the FastPix Dashboard, your **Access Token**
becomes `FASTPIX_USERNAME` and your **Secret Key** becomes `FASTPIX_PASSWORD`:

```bash
cp examples/.env.example examples/.env   # then edit it
export $(grep -v '^#' examples/.env | xargs)
```

Run any example:

```bash
php examples/create-upload.php
```

## Examples

| File | What it does |
| --- | --- |
| `create-upload.php` | Mint a signed direct-upload URL and print the upload command |
| `verify-webhook.php` | Verify a FastPix webhook signature (runs offline, no credentials) |
| `create-media.php` | Create media from a public URL |
| `create-playback-id.php` | Create a playback ID for a ready media (`php create-playback-id.php <mediaId>`) |
| `update-media-summary.php` | Generate an AI summary for a ready media (`php update-media-summary.php <mediaId>`) |
| `list-playlists.php` | List playlists |
| `list-live-streams.php` | List live streams |
| `create-live-stream.php` | Create a live stream |
| `list-video-views.php` | List video views (analytics) |
| `metrics-overall.php` | Overall values for a metric |
| `list-dimension-values.php` | Values seen for a metrics dimension |
| `drm-configurations.php` | List DRM configurations |

Some operations only work once a media has finished processing (status
`Ready`) — those examples take an existing media ID as an argument.

## Uploading a file after `create-upload.php`

`create-upload.php` gives you a signed URL. Your client uploads the file straight
to it, so the bytes never touch your server. We keep it simple and PUT the whole
file in one request — fine for small files. For larger ones you'll usually want a
resumable upload (chunked, with retries and progress); the same signed URL
supports that too.

```bash
curl -X PUT --upload-file video.mp4 -H "Content-Type: video/mp4" "<signed-url>"
```

Or from the browser, straight off a file input:

```js
const res = await fetch("/uploads", { method: "POST" });
const { url } = await res.json();
await fetch(url, {
  method: "PUT",
  headers: { "Content-Type": file.type || "application/octet-stream" },
  body: file,
});
```

The upload URL is created with `corsOrigin: "*"` so the browser can PUT from
anywhere — lock that down before you ship. More detail, resumable included:
https://fastpix.com/docs/upload-videos/upload-videos-from-device

## Laravel

There's a full Laravel upload-and-player integration in a separate repo, plus a
guide in the docs:

- https://github.com/FastPix/web-player-uploads-example-integrations/tree/main/laravel-uploader-and-player
- https://fastpix.com/docs/frameworks/laravel

## The `projects/` folder

`projects/php-quickstart/` is a small "copy from the README and run" project: it
loads a `.env` for you via `bootstrap.php` so you don't have to export variables
by hand. See its own README to get started.
