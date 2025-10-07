<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Models\Components;

use FastPix\Sdk\Models\Components\VideoInput;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Models\Components\VideoInput
 */
class VideoInputTest extends TestCase
{
    public function test_video_input_can_be_created_with_required_fields(): void
    {
        $videoInput = new VideoInput(
            type: 'video',
            url: 'https://example.com/video.mp4'
        );

        $this->assertEquals('video', $videoInput->type);
        $this->assertEquals('https://example.com/video.mp4', $videoInput->url);
    }

    public function test_video_input_with_all_fields(): void
    {
        $videoInput = new VideoInput(
            type: 'video',
            url: 'https://example.com/video.mp4',
            startTime: 0.0,
            endTime: 120.0,
            introUrl: 'https://example.com/intro.mp4',
            outroUrl: 'https://example.com/outro.mp4'
        );

        $this->assertEquals('video', $videoInput->type);
        $this->assertEquals('https://example.com/video.mp4', $videoInput->url);
        $this->assertEquals(0.0, $videoInput->startTime);
        $this->assertEquals(120.0, $videoInput->endTime);
        $this->assertEquals('https://example.com/intro.mp4', $videoInput->introUrl);
        $this->assertEquals('https://example.com/outro.mp4', $videoInput->outroUrl);
    }

    public function test_video_input_with_optional_fields_as_null(): void
    {
        $videoInput = new VideoInput(
            type: 'video',
            url: 'https://example.com/video.mp4',
            startTime: null,
            endTime: null,
            introUrl: null,
            outroUrl: null
        );

        $this->assertEquals('video', $videoInput->type);
        $this->assertEquals('https://example.com/video.mp4', $videoInput->url);
        $this->assertNull($videoInput->startTime);
        $this->assertNull($videoInput->endTime);
        $this->assertNull($videoInput->introUrl);
        $this->assertNull($videoInput->outroUrl);
    }

    public function test_video_input_properties_are_public(): void
    {
        $videoInput = new VideoInput(
            type: 'video',
            url: 'https://example.com/video.mp4'
        );

        $this->assertObjectHasProperty('type', $videoInput);
        $this->assertObjectHasProperty('url', $videoInput);
        $this->assertObjectHasProperty('startTime', $videoInput);
        $this->assertObjectHasProperty('endTime', $videoInput);
        $this->assertObjectHasProperty('introUrl', $videoInput);
        $this->assertObjectHasProperty('outroUrl', $videoInput);
    }
}
