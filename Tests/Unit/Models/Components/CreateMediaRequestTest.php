<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Models\Components;

use FastPix\Sdk\Models\Components\AudioInput;
use FastPix\Sdk\Models\Components\CreateMediaRequest;
use FastPix\Sdk\Models\Components\CreateMediaRequestAccessPolicy;
use FastPix\Sdk\Models\Components\SubtitleInput;
use FastPix\Sdk\Models\Components\VideoInput;
use FastPix\Sdk\Models\Components\WatermarkInput;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Models\Components\CreateMediaRequest
 */
class CreateMediaRequestTest extends TestCase
{
    public function test_create_media_request_with_video_input(): void
    {
        $videoInput = new VideoInput(
            type: 'video',
            url: 'https://example.com/video.mp4'
        );

        $request = new CreateMediaRequest(
            inputs: [$videoInput],
            metadata: ['title' => 'Test Video'],
            accessPolicy: CreateMediaRequestAccessPolicy::Public
        );

        $this->assertCount(1, $request->inputs);
        $this->assertInstanceOf(VideoInput::class, $request->inputs[0]);
        $this->assertEquals('video', $request->inputs[0]->type);
        $this->assertEquals('https://example.com/video.mp4', $request->inputs[0]->url);
        $this->assertEquals(['title' => 'Test Video'], $request->metadata);
        $this->assertEquals(CreateMediaRequestAccessPolicy::Public, $request->accessPolicy);
    }

    public function test_create_media_request_with_multiple_inputs(): void
    {
        $videoInput = new VideoInput(
            type: 'video',
            url: 'https://example.com/video.mp4'
        );

        $audioInput = new AudioInput(
            type: \FastPix\Sdk\Models\Components\AudioInputType::Audio,
            swapTrackUrl: 'https://example.com/audio.mp3'
        );

        $request = new CreateMediaRequest(
            inputs: [$videoInput, $audioInput],
            metadata: ['title' => 'Multi Input Video'],
            accessPolicy: CreateMediaRequestAccessPolicy::Private
        );

        $this->assertCount(2, $request->inputs);
        $this->assertInstanceOf(VideoInput::class, $request->inputs[0]);
        $this->assertInstanceOf(AudioInput::class, $request->inputs[1]);
        $this->assertEquals(CreateMediaRequestAccessPolicy::Private, $request->accessPolicy);
    }

    public function test_create_media_request_with_empty_metadata(): void
    {
        $videoInput = new VideoInput(
            type: 'video',
            url: 'https://example.com/video.mp4'
        );

        $request = new CreateMediaRequest(
            inputs: [$videoInput],
            metadata: [],
            accessPolicy: CreateMediaRequestAccessPolicy::Public
        );

        $this->assertEmpty($request->metadata);
    }

    public function test_create_media_request_with_null_metadata(): void
    {
        $videoInput = new VideoInput(
            type: 'video',
            url: 'https://example.com/video.mp4'
        );

        $request = new CreateMediaRequest(
            inputs: [$videoInput],
            metadata: null,
            accessPolicy: CreateMediaRequestAccessPolicy::Public
        );

        $this->assertNull($request->metadata);
    }

    public function test_create_media_request_with_subtitle_input(): void
    {
        $subtitleInput = new SubtitleInput(
            type: 'subtitle',
            url: 'https://example.com/subtitle.vtt',
            languageName: 'English',
            languageCode: \FastPix\Sdk\Models\Components\LanguageCode::EnUS
        );

        $request = new CreateMediaRequest(
            inputs: [$subtitleInput],
            metadata: ['title' => 'Video with Subtitles'],
            accessPolicy: CreateMediaRequestAccessPolicy::Public
        );

        $this->assertCount(1, $request->inputs);
        $this->assertInstanceOf(SubtitleInput::class, $request->inputs[0]);
        $this->assertEquals('subtitle', $request->inputs[0]->type);
        $this->assertEquals('English', $request->inputs[0]->languageName);
    }

    public function test_create_media_request_with_watermark_input(): void
    {
        $watermarkInput = new WatermarkInput(
            type: \FastPix\Sdk\Models\Components\WatermarkInputType::Watermark,
            url: 'https://example.com/watermark.png',
            placement: new \FastPix\Sdk\Models\Components\Placement(
                xAlign: \FastPix\Sdk\Models\Components\XAlign::Right,
                yAlign: \FastPix\Sdk\Models\Components\YAlign::Top
            )
        );

        $request = new CreateMediaRequest(
            inputs: [$watermarkInput],
            metadata: ['title' => 'Video with Watermark'],
            accessPolicy: CreateMediaRequestAccessPolicy::Public
        );

        $this->assertCount(1, $request->inputs);
        $this->assertInstanceOf(WatermarkInput::class, $request->inputs[0]);
        $this->assertEquals(\FastPix\Sdk\Models\Components\WatermarkInputType::Watermark, $request->inputs[0]->type);
        $this->assertInstanceOf(\FastPix\Sdk\Models\Components\Placement::class, $request->inputs[0]->placement);
    }
}
