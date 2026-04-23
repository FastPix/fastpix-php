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
        if ($context->operationID !== self::OPERATION_ID) {
            return $response;
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode < 200 || $statusCode >= 300) {
            return $response;
        }

        $contentType = $response->getHeaderLine('Content-Type');
        if (! str_contains(strtolower($contentType), 'application/json')) {
            return $response;
        }

        $body = (string) $response->getBody();
        if ($body === '') {
            return $response;
        }

        /** @var mixed $decoded */
        $decoded = json_decode($body, true);
        if (! is_array($decoded) || json_last_error() !== JSON_ERROR_NONE || self::isList($decoded)) {
            return $response;
        }

        if (! isset($decoded['data']) || ! is_array($decoded['data']) || self::isList($decoded['data'])) {
            return $response;
        }

        if (! isset($decoded['data']['events']) || ! is_array($decoded['data']['events']) || ! self::isList($decoded['data']['events'])) {
            return $response;
        }

        $rebuiltEvents = [];
        foreach ($decoded['data']['events'] as $event) {
            if (! is_array($event) || self::isList($event)) {
                $rebuiltEvents[] = $event;

                continue;
            }

            $rebuiltEvent = [];
            foreach ($event as $key => $value) {
                $newKey = self::OUTER_MAP[$key] ?? $key;
                if ($newKey === 'event_details' && is_array($value) && ! self::isList($value)) {
                    $rebuiltInner = [];
                    foreach ($value as $innerKey => $innerValue) {
                        $rebuiltInner[self::INNER_MAP[$innerKey] ?? $innerKey] = $innerValue;
                    }
                    $rebuiltEvent[$newKey] = $rebuiltInner;
                } else {
                    $rebuiltEvent[$newKey] = $value;
                }
            }
            $rebuiltEvents[] = $rebuiltEvent;
        }

        $decoded['data']['events'] = $rebuiltEvents;

        $rewritten = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($rewritten === false) {
            return $response;
        }

        return $response->withBody(Utils::streamFor($rewritten));
    }

    /**
     * @param  array<int|string, mixed>  $value
     */
    private static function isList(array $value): bool
    {
        return $value === [] || array_is_list($value);
    }
}
