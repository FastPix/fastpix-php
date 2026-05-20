# PHP SDK Test Project

This directory contains example scripts that you can copy from the README and run directly.

## Quick Start

1. **Copy environment variables template:**

```bash
cp .env.example .env
```

2. **Edit `.env` file with your credentials:**

```bash
FASTPIX_USERNAME=your-actual-username
FASTPIX_PASSWORD=your-actual-password
FASTPIX_SERVER_URL=https://api.fastpix.com/v1/
```

3. **Run any example script:**

```bash
php list_media.php
php get_media.php <mediaId>
php list_playlists.php
php list_signing_keys.php
php create_media_example.php
```

## How to Use Examples from README

1. **Copy the example code** from the README (e.g., from `USAGE.md` or `README.md`)

2. **Create a new PHP file** in this directory (e.g., `my_example.php`)

3. **Replace the SDK initialization** with:

```php
<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

// Your code from README here - just replace the SDK initialization part
$sdk = getSDK();

// Rest of your code...
```

4. **Run it:**

```bash
php my_example.php
```

## Example: Converting README Code

**From README:**
```php
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
```

**In test project:**
```php
<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$sdk = getSDK(); // That's it! Credentials loaded from .env
```

## Available Example Scripts

- `list_media.php` - List all media
- `get_media.php` - Get media by ID (takes mediaId as argument)
- `list_playlists.php` - List all playlists
- `list_signing_keys.php` - List signing keys
- `create_media_example.php` - Create media (exact README example)

## Environment Variables

All scripts use environment variables from `.env` file:

- `FASTPIX_USERNAME` - Your FastPix username (required)
- `FASTPIX_PASSWORD` - Your FastPix password (required)
- `FASTPIX_SERVER_URL` - API server URL (optional, defaults to https://api.fastpix.com/v1/)

## Troubleshooting

**Error: "Missing FASTPIX_USERNAME or FASTPIX_PASSWORD"**
- Make sure you've created `.env` file from `.env.example`
- Check that `.env` file contains your actual credentials

**Error: "Class not found" or autoload errors**
- Make sure you've run `composer install` in the SDK root directory (parent of `test-project/`)

**Error: "404 Not Found"**
- Check that your credentials are correct
- Verify the media ID or resource ID exists in your account
