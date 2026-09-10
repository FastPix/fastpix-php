<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests;

use FastPix\Sdk\LivePlayback;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Playback;
use FastPix\Sdk\Serializer\Serializer;
use FastPix\Sdk\Utils\JSON;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** Wire-format contracts for media duration, live recording and playback restrictions. No network. */
final class ModelContractTest extends TestCase
{
    private const COMPONENTS = '\\FastPix\\Sdk\\Models\\Components\\';
    private const OPERATIONS = '\\FastPix\\Sdk\\Models\\Operations\\';

    private Serializer $serializer;

    protected function setUp(): void
    {
        $this->serializer = JSON::createSerializer();
    }

    /** @return array<string, array{string}> */
    public static function durationModels(): array
    {
        $names = ['GetMediaDetailResponse', 'GetAllMediaResponse', 'UpdateMedia', 'Media', 'SourceAccessMedia', 'LiveMediaClips', 'MediaClipResponseData', 'PlaylistByIdResponseMediaListItem'];

        return array_combine($names, array_map(static fn (string $n): array => [$n], $names));
    }

    #[DataProvider('durationModels')]
    public function test_duration_is_optional_float_seconds(string $model): void
    {
        $class = self::COMPONENTS.$model;

        $fractional = $this->serializer->deserialize('{"duration":145.821315}', $class, 'json');
        self::assertSame(145.821315, $fractional->duration);

        $integer = $this->serializer->deserialize('{"duration":10}', $class, 'json');
        self::assertSame(10.0, $integer->duration);

        $absent = $this->serializer->deserialize('{}', $class, 'json');
        self::assertNull($absent->duration);

        $json = json_decode($this->serializer->serialize($fractional, 'json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(145.821315, $json['duration']);

        // The serializer casts scalars leniently: a legacy clock string is never a valid seconds value.
        $legacy = $this->serializer->deserialize('{"duration":"00:02:25"}', $class, 'json');
        self::assertSame(0.0, $legacy->duration);

        $type = (new \ReflectionProperty($class, 'duration'))->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        self::assertSame('float', $type->getName());
        self::assertTrue($type->allowsNull());
    }

    #[DataProvider('durationModels')]
    public function test_frame_rate_is_optional_string(string $model): void
    {
        $class = self::COMPONENTS.$model;

        $parsed = $this->serializer->deserialize('{"frameRate":"30/1"}', $class, 'json');
        self::assertSame('30/1', $parsed->frameRate);

        $absent = $this->serializer->deserialize('{}', $class, 'json');
        self::assertNull($absent->frameRate);

        $json = json_decode($this->serializer->serialize($parsed, 'json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('30/1', $json['frameRate']);

        $type = (new \ReflectionProperty($class, 'frameRate'))->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        self::assertSame('string', $type->getName());
        self::assertTrue($type->allowsNull());
    }

    public function test_enable_recording_defaults_true_and_round_trips(): void
    {
        $default = $this->toArray(new Components\InputMediaSettings());
        self::assertTrue($default['enableRecording']);

        $request = new Components\CreateLiveStreamRequest(
            playbackSettings: new Components\PlaybackSettings(),
            inputMediaSettings: new Components\InputMediaSettings(enableRecording: false),
        );
        self::assertFalse($this->toArray($request)['inputMediaSettings']['enableRecording']);

        $parsed = $this->serializer->deserialize('{"enableRecording":false}', self::COMPONENTS.'InputMediaSettings', 'json');
        self::assertFalse($parsed->enableRecording);
    }

    private function restrictions(): Components\PlaybackIdAccessRestrictions
    {
        return new Components\PlaybackIdAccessRestrictions(
            domains: new Components\PlaybackIdDomains(defaultPolicy: Components\PolicyAction::Deny, allow: ['example.com'], deny: []),
            userAgents: new Components\PlaybackIdUserAgents(defaultPolicy: Components\PolicyAction::Allow, allow: [], deny: []),
        );
    }

    /** @return array<string, array{object}> */
    public static function restrictionRequestModels(): array
    {
        return [
            'PlaybackIdRequest' => [new Components\PlaybackIdRequest()],
            'PlaybackSettings' => [new Components\PlaybackSettings()],
        ];
    }

    #[DataProvider('restrictionRequestModels')]
    public function test_access_restrictions_serialize_with_wire_names(object $model): void
    {
        self::assertArrayNotHasKey('accessRestrictions', $this->toArray($model));

        $model->accessRestrictions = $this->restrictions();
        $json = $this->toArray($model);
        self::assertSame('deny', $json['accessRestrictions']['domains']['defaultPolicy']);
        self::assertSame(['example.com'], $json['accessRestrictions']['domains']['allow']);
        self::assertSame([], $json['accessRestrictions']['domains']['deny']);
        self::assertSame('allow', $json['accessRestrictions']['userAgents']['defaultPolicy']);
    }

    /** @return array<string, array{string}> */
    public static function restrictionResponseModels(): array
    {
        return ['PlaybackIdSuccessResponseData' => ['PlaybackIdSuccessResponseData'], 'PlaybackIdResponse' => ['PlaybackIdResponse']];
    }

    #[DataProvider('restrictionResponseModels')]
    public function test_access_restrictions_parse_on_responses(string $model): void
    {
        $class = self::COMPONENTS.$model;
        $with = $this->serializer->deserialize('{"id":"p1","accessPolicy":"public","accessRestrictions":{"domains":{"defaultPolicy":"deny","allow":["example.com"],"deny":[]},"userAgents":{"defaultPolicy":"allow","allow":[],"deny":[]}}}', $class, 'json');
        self::assertSame(['example.com'], $with->accessRestrictions->domains->allow);
        self::assertSame(Components\PolicyAction::Allow, $with->accessRestrictions->userAgents->defaultPolicy);

        $without = $this->serializer->deserialize('{"id":"p1","accessPolicy":"public"}', $class, 'json');
        self::assertNull($without->accessRestrictions);
    }

    public function test_playback_id_success_response_envelope(): void
    {
        $class = self::COMPONENTS.'PlaybackIdSuccessResponse';
        $with = $this->serializer->deserialize('{"success":true,"data":{"id":"p1","accessRestrictions":{"domains":{"defaultPolicy":"allow"}}}}', $class, 'json');
        self::assertSame(Components\PolicyAction::Allow, $with->data->accessRestrictions->domains->defaultPolicy);

        $without = $this->serializer->deserialize('{"success":true,"data":{"id":"p1","accessPolicy":"public"}}', $class, 'json');
        self::assertNull($without->data->accessRestrictions);
    }

    /** @return array<string, array{string}> */
    public static function liveRestrictionOps(): array
    {
        return ['domains' => ['UpdateLiveStreamDomainRestrictions'], 'user-agents' => ['UpdateLiveStreamUserAgentRestrictions']];
    }

    #[DataProvider('liveRestrictionOps')]
    public function test_live_restriction_operation_models(string $op): void
    {
        $bodyClass = self::OPERATIONS.$op.'RequestBody';
        $policyClass = self::OPERATIONS.$op.'DefaultPolicy';

        $defaults = $this->toArray(new $bodyClass());
        self::assertSame(['defaultPolicy' => 'allow'], $defaults);

        $full = $this->toArray(new $bodyClass(allow: ['example.com'], deny: [], defaultPolicy: $policyClass::Deny));
        self::assertSame(['allow' => ['example.com'], 'deny' => [], 'defaultPolicy' => 'deny'], $full);

        $requestClass = self::OPERATIONS.$op.'Request';
        $request = new $requestClass(streamId: 's1', playbackId: 'p1', body: new $bodyClass());
        self::assertSame('s1', $request->streamId);
        self::assertFalse(property_exists($request, 'mediaId'));

        $response = $this->serializer->deserialize('{"success":true,"data":{"defaultPolicy":"allow","allow":["a.com"],"deny":["b.io"]}}', self::OPERATIONS.$op.'ResponseBody', 'json');
        self::assertTrue($response->success);
        self::assertSame('allow', $response->data->defaultPolicy);
        self::assertSame(['a.com'], $response->data->allow);
        self::assertSame(['b.io'], $response->data->deny);
    }

    public function test_track_items_carry_title(): void
    {
        $json = '{"tracks":[{"id":"v","type":"video","title":"My track title"},{"id":"a","type":"audio","languageCode":"und","title":"My track title"},{"id":"s","type":"subtitle","languageCode":"en-US","title":"My track title"}]}';
        foreach (['GetAllMediaResponse', 'GetMediaDetailResponse', 'UpdateMedia'] as $model) {
            $media = $this->serializer->deserialize($json, self::COMPONENTS.$model, 'json');
            foreach ($media->tracks as $track) {
                self::assertSame('My track title', $track->title, $model.' '.get_class($track));
            }
        }
    }

    public function test_restriction_methods_exist_on_both_resources(): void
    {
        foreach (['updateDomainRestrictions', 'updateUserAgentRestrictions'] as $method) {
            self::assertTrue(method_exists(LivePlayback::class, $method));
            self::assertTrue(method_exists(Playback::class, $method));
        }
    }

    /** @return array<string, mixed> */
    private function toArray(object $model): array
    {
        return json_decode($this->serializer->serialize($model, 'json'), true, 512, JSON_THROW_ON_ERROR);
    }
}
