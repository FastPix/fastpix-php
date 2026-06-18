<?php

declare(strict_types=1);

namespace FastPix\Sdk\Hooks;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;

/**
 * AfterSuccess hook for the `get_video_view_details` operation.
 *
 * The live API at GET /v1/data/viewlist/{viewId} returns abbreviated wire
 * keys on each element of data.events[] while the OpenAPI spec documents
 * the fully spelled-out names. Without this hook the strongly-typed
 * deserializer drops every event key and the caller sees empty events.
 *
 * This hook rewrites the JSON body so the generated deserializer sees
 * spec-shaped keys. It is a no-op for every other operation.
 */
class EventsFieldRemapHook implements AfterSuccessHook
{
    private const OPERATION_ID = 'get_video_view_details';

    /**
     * @var array<string, string>
     */
    private const OUTER_MAP = [
        'pt' => 'player_playhead_time',
        'e' => 'event_name',
        'd' => 'event_details',
        'vt' => 'viewer_time',
        'et' => 'event_time',
    ];

    /**
     * @var array<string, string>
     */
    private const INNER_MAP = [
        'br' => 'bitrate',
        'h' => 'height',
        'w' => 'width',
        'cd' => 'codec',
        'host' => 'hostName',
        'txt' => 'text',
        'c' => 'code',
        'err' => 'error',
        't' => 'type',
        'u' => 'url',
    ];

    public function afterSuccess(AfterSuccessContext $context, ResponseInterface $response): ResponseInterface
    {
        $decoded = $this->shouldRemap($context, $response)
            ? $this->decodeRemappableBody((string) $response->getBody())
            : null;

        if ($decoded !== null) {
            $decoded['data']['events'] = self::remapEvents($decoded['data']['events']);

            $rewritten = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            if ($rewritten !== false) {
                return $response->withBody(Utils::streamFor($rewritten));
            }
        }

        return $response;
    }

    /**
     * Whether this response is the JSON success payload of the target operation.
     */
    private function shouldRemap(AfterSuccessContext $context, ResponseInterface $response): bool
    {
        if ($context->operationID !== self::OPERATION_ID) {
            return false;
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode < 200 || $statusCode >= 300) {
            return false;
        }

        $contentType = strtolower($response->getHeaderLine('Content-Type'));

        return str_contains($contentType, 'application/json');
    }

    /**
     * Decode the body and return it only when it is shaped like a remappable
     * payload (an object with a `data` object holding an `events` list).
     *
     * @return array{data: array{events: list<mixed>}, ...}|null
     */
    private function decodeRemappableBody(string $body): ?array
    {
        if ($body === '') {
            return null;
        }

        /** @var mixed $decoded */
        $decoded = json_decode($body, true);

        $isRemappable = is_array($decoded)
            && json_last_error() === JSON_ERROR_NONE
            && ! self::isList($decoded)
            && isset($decoded['data']) && is_array($decoded['data']) && ! self::isList($decoded['data'])
            && isset($decoded['data']['events']) && is_array($decoded['data']['events']) && self::isList($decoded['data']['events']);

        return $isRemappable ? $decoded : null;
    }

    /**
     * @param  list<mixed>  $events
     * @return list<mixed>
     */
    private static function remapEvents(array $events): array
    {
        $rebuiltEvents = [];
        foreach ($events as $event) {
            if (! is_array($event) || self::isList($event)) {
                $rebuiltEvents[] = $event;

                continue;
            }

            $rebuiltEvents[] = self::remapEvent($event);
        }

        return $rebuiltEvents;
    }

    /**
     * @param  array<string, mixed>  $event
     * @return array<string, mixed>
     */
    private static function remapEvent(array $event): array
    {
        $rebuiltEvent = [];
        foreach ($event as $key => $value) {
            $newKey = self::OUTER_MAP[$key] ?? $key;
            if ($newKey === 'event_details' && is_array($value) && ! self::isList($value)) {
                $rebuiltEvent[$newKey] = self::remapInner($value);
            } else {
                $rebuiltEvent[$newKey] = $value;
            }
        }

        return $rebuiltEvent;
    }

    /**
     * @param  array<string, mixed>  $inner
     * @return array<string, mixed>
     */
    private static function remapInner(array $inner): array
    {
        $rebuiltInner = [];
        foreach ($inner as $innerKey => $innerValue) {
            $rebuiltInner[self::INNER_MAP[$innerKey] ?? $innerKey] = $innerValue;
        }

        return $rebuiltInner;
    }

    /**
     * @param  array<int|string, mixed>  $value
     */
    private static function isList(array $value): bool
    {
        return $value === [] || array_is_list($value);
    }
}
