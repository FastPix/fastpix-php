<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests;

use FastPix\Sdk\Fastpixsdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Errors\APIException;
use FastPix\Sdk\Models\Operations;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** Wire-level checks for the live playback restriction endpoints against a mocked transport. */
final class LivePlaybackTest extends TestCase
{
    /** @var array<int, array{request: RequestInterface}> */
    private array $history = [];

    private function sdk(Response ...$responses): Fastpixsdk
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));

        return Fastpixsdk::builder()
            ->setClient(new Client(['handler' => $stack]))
            ->setSecurity(new Components\Security(username: 'u', password: 'p'))
            ->setServerUrl('https://api.example.test/v1')
            ->build();
    }

    private function ok(): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], '{"success":true,"data":{"defaultPolicy":"deny","allow":["example.com"],"deny":[]}}');
    }

    public function test_update_domain_restrictions_sends_flat_patch(): void
    {
        $response = $this->sdk($this->ok())->livePlayback->updateDomainRestrictions(
            new Operations\UpdateLiveStreamDomainRestrictionsRequestBody(allow: ['example.com'], deny: [], defaultPolicy: Operations\UpdateLiveStreamDomainRestrictionsDefaultPolicy::Deny),
            'stream-1',
            'pb-1',
        );

        $request = $this->history[0]['request'];
        self::assertSame('PATCH', $request->getMethod());
        self::assertSame('/v1/live/streams/stream-1/playback-ids/pb-1/domains', $request->getUri()->getPath());
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
        self::assertSame(['allow' => ['example.com'], 'deny' => [], 'defaultPolicy' => 'deny'], json_decode((string) $request->getBody(), true));
        self::assertInstanceOf(Operations\UpdateLiveStreamDomainRestrictionsResponseBody::class, $response->object);
        self::assertSame(['example.com'], $response->object->data->allow);
    }

    public function test_update_user_agent_restrictions_sends_flat_patch(): void
    {
        $response = $this->sdk($this->ok())->livePlayback->updateUserAgentRestrictions(
            new Operations\UpdateLiveStreamUserAgentRestrictionsRequestBody(deny: ['PostmanRuntime/7.29.0']),
            'stream-1',
            'pb-1',
        );

        $request = $this->history[0]['request'];
        self::assertSame('PATCH', $request->getMethod());
        self::assertSame('/v1/live/streams/stream-1/playback-ids/pb-1/user-agents', $request->getUri()->getPath());
        self::assertSame(['deny' => ['PostmanRuntime/7.29.0'], 'defaultPolicy' => 'allow'], json_decode((string) $request->getBody(), true));
        self::assertTrue($response->object->success);
    }

    public function test_client_error_throws_api_exception(): void
    {
        $sdk = $this->sdk(new Response(404, ['Content-Type' => 'application/json'], '{"success":false,"error":{"code":404,"message":"not found"}}'));

        $this->expectException(APIException::class);
        $this->expectExceptionCode(404);
        $sdk->livePlayback->updateDomainRestrictions(new Operations\UpdateLiveStreamDomainRestrictionsRequestBody(), 'stream-1', 'pb-1');
    }
}
