<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests;

use FastPix\Sdk\Fastpixsdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Serializer\Serializer;
use FastPix\Sdk\Utils\JSON;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * An unknown enum value from the API is kept as a raw string instead of failing the
 * whole response; a known value still resolves to the enum. Guards against a future
 * regeneration re-closing these enums or bringing back the 1080p default.
 */
final class ResponseEnumToleranceTest extends TestCase
{
    private const COMPONENTS = '\\FastPix\\Sdk\\Models\\Components\\';

    private Serializer $serializer;

    protected function setUp(): void
    {
        $this->serializer = JSON::createSerializer();
    }

    /**
     * Response media models carrying sourceResolution/maxResolution/status enums.
     *
     * @return array<string, array{string}>
     */
    public static function resolutionModels(): array
    {
        $names = ['Media', 'GetMediaDetailResponse', 'GetAllMediaResponse', 'SourceAccessMedia', 'UpdateMedia', 'LiveMediaClips'];

        return array_combine($names, array_map(static fn (string $n): array => [$n], $names));
    }

    #[DataProvider('resolutionModels')]
    public function test_unknown_resolution_is_preserved_as_string(string $model): void
    {
        $class = self::COMPONENTS.$model;

        // "1920" and "0" turn up in production and aren't in the spec enum.
        foreach (['1920', '0', 'NA'] as $unknown) {
            $parsed = $this->serializer->deserialize(sprintf('{"sourceResolution":"%s"}', $unknown), $class, 'json');
            self::assertSame($unknown, $parsed->sourceResolution, $model.' should keep the raw unknown resolution');

            // and survive a round-trip back to the wire.
            $json = json_decode($this->serializer->serialize($parsed, 'json'), true, 512, JSON_THROW_ON_ERROR);
            self::assertSame($unknown, $json['sourceResolution'], $model.' should re-serialize the raw value');
        }
    }

    #[DataProvider('resolutionModels')]
    public function test_known_resolution_still_resolves_to_enum_member(string $model): void
    {
        $class = self::COMPONENTS.$model;

        $parsed = $this->serializer->deserialize('{"sourceResolution":"1080p","maxResolution":"720p"}', $class, 'json');
        // A value in the enum binds to the enum, not the string fallback.
        self::assertInstanceOf(\BackedEnum::class, $parsed->sourceResolution, $model.' known sourceResolution should be an enum');
        self::assertSame('1080p', $parsed->sourceResolution->value);
        self::assertInstanceOf(\BackedEnum::class, $parsed->maxResolution, $model.' known maxResolution should be an enum');
        self::assertSame('720p', $parsed->maxResolution->value);
    }

    #[DataProvider('resolutionModels')]
    public function test_unknown_status_is_preserved_as_string(string $model): void
    {
        $class = self::COMPONENTS.$model;

        $parsed = $this->serializer->deserialize('{"status":"SomethingNew"}', $class, 'json');
        self::assertSame('SomethingNew', $parsed->status, $model.' should keep an unknown status');
    }

    #[DataProvider('resolutionModels')]
    public function test_absent_max_resolution_uses_default(string $model): void
    {
        $class = self::COMPONENTS.$model;

        // maxResolution keeps the spec default, so an omitted field reads as 1080p.
        $parsed = $this->serializer->deserialize('{"status":"Ready"}', $class, 'json');
        self::assertInstanceOf(\BackedEnum::class, $parsed->maxResolution, $model);
        self::assertSame('1080p', $parsed->maxResolution->value);
    }

    public function test_unknown_mp4_support_type_is_preserved(): void
    {
        // A nested enum carrier (mp4Support[].type / .status) is open too.
        $parsed = $this->serializer->deserialize('{"mp4Support":[{"type":"brand_new_tier","status":"cooking"}]}', self::COMPONENTS.'Media', 'json');
        self::assertSame('brand_new_tier', $parsed->mp4Support[0]->type);
        self::assertSame('cooking', $parsed->mp4Support[0]->status);
    }

    public function test_get_media_call_survives_unknown_resolution_end_to_end(): void
    {
        // A whole getMedia call over a media with an out-of-enum resolution must return.
        $body = '{"success":true,"data":{"id":"m1","status":"Ready","sourceResolution":"1920","maxResolution":"1080p"}}';
        $sdk = $this->sdk(new Response(200, ['Content-Type' => 'application/json'], $body));

        $response = $sdk->manageVideos->getMedia('m1');

        self::assertSame(200, $response->statusCode);
        self::assertNotNull($response->object);
        $media = $response->object->data;
        self::assertSame('1920', $media->sourceResolution);
        self::assertInstanceOf(\BackedEnum::class, $media->maxResolution);
        self::assertSame('1080p', $media->maxResolution->value);
        self::assertInstanceOf(\BackedEnum::class, $media->status);
        self::assertSame('Ready', $media->status->value);
    }

    private function sdk(Response $response): Fastpixsdk
    {
        $stack = HandlerStack::create(new MockHandler([$response]));

        return Fastpixsdk::builder()
            ->setClient(new Client(['handler' => $stack]))
            ->setSecurity(new Components\Security(username: 'u', password: 'p'))
            ->setServerUrl('https://api.example.test/v1')
            ->build();
    }
}
