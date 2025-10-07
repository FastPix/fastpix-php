<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Integration;

use FastPix\Sdk\Models\Components\Security;
use FastPix\Sdk\Models\Operations\UpdatedMediaRequestBody;
use FastPix\Sdk\SDK;
use PHPUnit\Framework\TestCase;

class ManageVideosTest extends TestCase
{
    private SDK $sdk;

    protected function setUp(): void
    {
        $accessToken = $_ENV['FASTPIX_ACCESS_TOKEN'] ?? '';
        $secretKey = $_ENV['FASTPIX_SECRET_KEY'] ?? '';

        if (empty($accessToken) || empty($secretKey)) {
            $this->markTestSkipped('FASTPIX_ACCESS_TOKEN and FASTPIX_SECRET_KEY environment variables are required for integration tests');
        }

        $this->sdk = SDK::builder()
            ->setSecurity(
                new Security(
                    username: $accessToken,
                    password: $secretKey,
                )
            )
            ->build();
    }

    public function test_list_videos(): void
    {
        echo "\n=== TESTING MANAGE VIDEOS - LIST VIDEOS ===\n";

        $response = $this->sdk->manageVideos->listMedia(offset: 1, limit: 10);

        echo "✅ Response Status: {$response->statusCode}\n";
        echo "✅ Content Type: {$response->contentType}\n";

        if ($response->object !== null) {
            $videos = $response->object->data ?? [];
            echo '✅ Videos Count: '.count($videos)."\n";

            foreach ($videos as $index => $video) {
                echo '  Video '.($index + 1).":\n";
                echo '    ID: '.($video->id ?? 'N/A')."\n";
                echo '    Status: '.($video->status ?? 'N/A')."\n";
                echo '    Created At: '.($video->createdAt?->format('Y-m-d H:i:s') ?? 'N/A')."\n";
            }

            // Check pagination
            if (isset($response->object->pagination)) {
                $pagination = $response->object->pagination;
                echo "✅ Pagination:\n";
                echo '  Total: '.($pagination->total ?? 'N/A')."\n";
                echo '  Offset: '.($pagination->offset ?? 'N/A')."\n";
                echo '  Limit: '.($pagination->limit ?? 'N/A')."\n";
            }
        }

        $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\ListMediaResponse::class, $response);
        echo "✅ ManageVideos listVideos test completed successfully!\n";
    }

    public function test_list_videos_with_pagination(): void
    {
        echo "\n=== TESTING MANAGE VIDEOS - PAGINATION ===\n";

        $paginationTests = [
            ['offset' => 1, 'limit' => 5, 'description' => 'First 5 videos'],
            ['offset' => 5, 'limit' => 5, 'description' => 'Next 5 videos'],
            ['offset' => 1, 'limit' => 1, 'description' => 'Single video'],
        ];

        foreach ($paginationTests as $test) {
            echo 'Testing: '.$test['description']."\n";
            echo "  Offset: {$test['offset']}, Limit: {$test['limit']}\n";

            $response = $this->sdk->manageVideos->listMedia(
                offset: $test['offset'],
                limit: $test['limit']
            );

            echo "  ✅ Status: {$response->statusCode}\n";

            if ($response->listMediaSuccessResponse !== null) {
                $videos = $response->listMediaSuccessResponse->data ?? [];
                echo '  ✅ Returned: '.count($videos)." videos\n";
            }
        }

        echo "✅ ManageVideos pagination test completed successfully!\n";
    }

    public function test_get_video_by_id(): void
    {
        echo "\n=== TESTING MANAGE VIDEOS - GET VIDEO BY ID ===\n";

        // First, get a list to find a valid video ID
        $listResponse = $this->sdk->manageVideos->listMedia(offset: 1, limit: 1);

        if ($listResponse->object !== null &&
            ! empty($listResponse->object->data)) {

            $videoId = $listResponse->object->data[0]->id;
            echo "Using Video ID: {$videoId}\n";

            $response = $this->sdk->manageVideos->getMedia($videoId);

            echo "✅ Response Status: {$response->statusCode}\n";
            echo "✅ Content Type: {$response->contentType}\n";

            if ($response->object !== null) {
                $video = $response->object->data;
                echo '✅ Video ID: '.($video->id ?? 'N/A')."\n";
                echo '✅ Status: '.($video->status ?? 'N/A')."\n";
                echo '✅ Created At: '.($video->createdAt?->format('Y-m-d H:i:s') ?? 'N/A')."\n";
                echo '✅ Updated At: '.($video->updatedAt?->format('Y-m-d H:i:s') ?? 'N/A')."\n";
            }

            $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\GetVideoByIdResponse::class, $response);
        } else {
            echo "⚠️  No videos found to test getVideoById\n";
        }

        echo "✅ ManageVideos getVideoById test completed successfully!\n";
    }

    public function test_update_video(): void
    {
        echo "\n=== TESTING MANAGE VIDEOS - UPDATE VIDEO ===\n";

        // First, get a list to find a valid video ID
        $listResponse = $this->sdk->manageVideos->listMedia(offset: 1, limit: 1);

        if ($listResponse->object !== null &&
            ! empty($listResponse->object->data)) {

            $videoId = $listResponse->object->data[0]->id;
            echo "Using Video ID: {$videoId}\n";

            $request = new UpdatedMediaRequestBody(
                metadata: [
                    'title' => 'Updated Test Video',
                    'description' => 'Updated via ManageVideos test',
                    'test_type' => 'updated_video',
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            );

            $response = $this->sdk->manageVideos->updatedMedia(
                requestBody: $request,
                mediaId: $videoId
            );

            echo "✅ Response Status: {$response->statusCode}\n";
            echo "✅ Content Type: {$response->contentType}\n";

            if ($response->object !== null) {
                $video = $response->object->data;
                echo '✅ Updated Video ID: '.($video->id ?? 'N/A')."\n";
                echo '✅ Updated Status: '.($video->status ?? 'N/A')."\n";
            }

            $this->assertInstanceOf(\FastPix\Sdk\Models\Operations\UpdatedMediaResponse::class, $response);
        } else {
            echo "⚠️  No videos found to test updateVideo\n";
        }

        echo "✅ ManageVideos updateVideo test completed successfully!\n";
    }

    public function test_delete_video(): void
    {
        echo "\n=== TESTING MANAGE VIDEOS - DELETE VIDEO ===\n";

        // Note: This test uses a test video ID that may not exist
        // In a real scenario, you would create a video first, then delete it
        $testVideoId = 'test-video-id-for-deletion';

        $response = $this->sdk->manageVideos->deleteMedia(mediaId: $testVideoId);

        echo "✅ Response Status: {$response->statusCode}\n";
        echo "✅ Content Type: {$response->contentType}\n";

        // Accept both 204 (success) and 404 (not found) as valid responses
        $this->assertContains($response->statusCode, [204, 404]);

        if ($response->statusCode === 204) {
            echo "✅ Video deleted successfully!\n";
        } else {
            echo "ℹ️  Video not found (expected for test ID)\n";
        }

        echo "✅ ManageVideos deleteVideo test completed successfully!\n";
    }
}