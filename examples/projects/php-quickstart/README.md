# PHP quickstart

A small runnable project for the FastPix PHP SDK. Unlike the standalone examples
one level up, these scripts share a `bootstrap.php` that loads your credentials
from a `.env` file — so you can paste code from the README and run it without
exporting environment variables by hand.

## Setup

Install the SDK dependencies once, from the repo root:

```bash
composer install
```

Then add your credentials here:

```bash
cp .env.example .env   # then edit it with your Access Token / Secret Key
```

## Run

```bash
./run.sh list_media.php
./run.sh list_playlists.php
./run.sh get_media.php <mediaId>
./run.sh list_signing_keys.php
```

Or call PHP directly: `php list_media.php`.

## Writing your own

Copy any example from the SDK README, drop it in a new file here, and replace the
SDK setup with:

```php
<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$sdk = getSDK(); // credentials come from .env
// ...your code...
```

## Notes

- `listSigningKeys` needs an access token with system/admin permission.
- Listing offsets start at `1`, not `0`.
