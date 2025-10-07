<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use FastPix\Sdk\Models\Components;
use FastPix\Sdk\SDK;

/**
 * Simple script to test README examples with real credentials
 * Usage: FASTPIX_ACCESS_TOKEN=your_token FASTPIX_SECRET_KEY=your_key php Tests/TestWithCredentials.php
 */
class TestWithCredentials
{
    private SDK $sdk;
    private string $accessToken;
    private string $secretKey;

    public function __construct()
    {
        $this->accessToken = $_ENV['FASTPIX_ACCESS_TOKEN'] ?? '';
        $this->secretKey = $_ENV['FASTPIX_SECRET_KEY'] ?? '';

        if (empty($this->accessToken) || empty($this->secretKey)) {
            echo "❌ Error: FASTPIX_ACCESS_TOKEN and FASTPIX_SECRET_KEY environment variables are required.\n";
            echo "Usage: FASTPIX_ACCESS_TOKEN=your_token FASTPIX_SECRET_KEY=your_key php Tests/TestWithCredentials.php\n";
            exit(1);
        }

        $this->sdk = SDK::builder()
            ->setSecurity(
                new Components\Security(
                    username: $this->accessToken,
                    password: $this->secretKey,
                )
            )
            ->build();
    }

    public function runTests(): void
    {
        echo "🧪 Testing README Examples with Real Credentials\n";
        echo str_repeat('=', 50)."\n";
        echo 'Access Token: '.substr($this->accessToken, 0, 8)."...\n";
        echo 'Secret Key: '.substr($this->secretKey, 0, 8)."...\n\n";

        $this->testSigningKeyCreation();
        $this->testSigningKeyListing();
        $this->testMediaListing();
        $this->testDRMConfigurations();
        $this->testPlaylistListing();
        $this->testLiveStreamListing();
    }

    private function testSigningKeyCreation(): void
    {
        echo "🔑 Testing Signing Key Creation...\n";
        try {
            $response = $this->sdk->signingKeys->createSigningKey();

            if ($response->createResponse !== null) {
                echo "  ✅ Signing key created successfully!\n";
                echo '  📝 Key ID: '.$response->createResponse->id."\n";
                echo '  🔐 Private Key: '.substr($response->createResponse->privateKey, 0, 50)."...\n";
            } else {
                echo "  ❌ Failed to create signing key\n";
            }
        } catch (Exception $e) {
            echo '  ❌ Error creating signing key: '.$e->getMessage()."\n";
        }
        echo "\n";
    }

    private function testSigningKeyListing(): void
    {
        echo "📋 Testing Signing Key Listing...\n";
        try {
            $response = $this->sdk->signingKeys->listSigningKeys(
                limit: 5,
                offset: 1
            );

            if ($response->getAllSigningKeyResponse !== null) {
                echo "  ✅ Signing keys listed successfully!\n";
                echo '  📊 Total keys: '.count($response->getAllSigningKeyResponse->data)."\n";
            } else {
                echo "  ❌ Failed to list signing keys\n";
            }
        } catch (Exception $e) {
            echo '  ❌ Error listing signing keys: '.$e->getMessage()."\n";
        }
        echo "\n";
    }

    private function testMediaListing(): void
    {
        echo "📺 Testing Media Listing...\n";
        try {
            $response = $this->sdk->manageVideos->listMedia(
                limit: 5,
                offset: 1,
                orderBy: Components\SortOrder::Desc
            );

            if ($response->object !== null) {
                echo "  ✅ Media listed successfully!\n";
                echo '  📊 Total media items: '.count($response->object->data)."\n";
            } else {
                echo "  ❌ Failed to list media\n";
            }
        } catch (Exception $e) {
            echo '  ❌ Error listing media: '.$e->getMessage()."\n";
        }
        echo "\n";
    }

    private function testDRMConfigurations(): void
    {
        echo "🔒 Testing DRM Configurations...\n";
        try {
            $response = $this->sdk->drmConfigurations->listDrmConfigurations(
                limit: 5,
                offset: 1
            );

            if ($response->object !== null) {
                echo "  ✅ DRM configurations listed successfully!\n";
                echo '  📊 Total configurations: '.count($response->object->data)."\n";
            } else {
                echo "  ❌ Failed to list DRM configurations\n";
            }
        } catch (Exception $e) {
            echo '  ❌ Error listing DRM configurations: '.$e->getMessage()."\n";
        }
        echo "\n";
    }

    private function testPlaylistListing(): void
    {
        echo "📋 Testing Playlist Listing...\n";
        try {
            $response = $this->sdk->playlist->listPlaylists(
                limit: 5,
                offset: 1
            );

            if ($response->object !== null) {
                echo "  ✅ Playlists listed successfully!\n";
                echo '  📊 Total playlists: '.count($response->object->data)."\n";
            } else {
                echo "  ❌ Failed to list playlists\n";
            }
        } catch (Exception $e) {
            echo '  ❌ Error listing playlists: '.$e->getMessage()."\n";
        }
        echo "\n";
    }

    private function testLiveStreamListing(): void
    {
        echo "📡 Testing Live Stream Listing...\n";
        try {
            $response = $this->sdk->manageLiveStream->getAllStreams(
                limit: 5,
                offset: 1
            );

            if ($response->object !== null) {
                echo "  ✅ Live streams listed successfully!\n";
                echo '  📊 Total streams: '.count($response->object->data)."\n";
            } else {
                echo "  ❌ Failed to list live streams\n";
            }
        } catch (Exception $e) {
            echo '  ❌ Error listing live streams: '.$e->getMessage()."\n";
        }
        echo "\n";
    }
}

// Run the tests
$tester = new TestWithCredentials();
$tester->runTests();

echo "🎉 Testing completed!\n";
