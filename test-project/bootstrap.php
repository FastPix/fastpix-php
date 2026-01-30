<?php
/**
 * Bootstrap file for test project
 * Loads environment variables and sets up the SDK
 */

declare(strict_types=1);

// Load environment variables from .env file
function loadEnv(string $filePath): void
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Load .env file from test-project directory
$envPath = __DIR__ . '/.env';
loadEnv($envPath);

// Require Composer autoloader (from parent directory)
require_once __DIR__ . '/../vendor/autoload.php';

use FastPix\Sdk\Fastpixsdk;
use FastPix\Sdk\Models\Components;

/**
 * Get SDK instance with credentials from environment variables
 */
function getSDK(): Fastpixsdk
{
    $username = getenv('FASTPIX_USERNAME') ?: $_ENV['FASTPIX_USERNAME'] ?? '';
    $password = getenv('FASTPIX_PASSWORD') ?: $_ENV['FASTPIX_PASSWORD'] ?? '';
    $serverUrl = getenv('FASTPIX_SERVER_URL') ?: $_ENV['FASTPIX_SERVER_URL'] ?? null;

    if (empty($username) || empty($password)) {
        throw new RuntimeException(
            'Missing FASTPIX_USERNAME or FASTPIX_PASSWORD environment variables. ' .
            'Please create a .env file based on .env.example'
        );
    }

    $builder = Fastpixsdk::builder()
        ->setSecurity(
            new Components\Security(
                username: $username,
                password: $password,
            )
        );

    if ($serverUrl !== null && $serverUrl !== '') {
        $builder = $builder->setServerUrl($serverUrl);
    }

    return $builder->build();
}
