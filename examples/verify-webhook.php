<?php

declare(strict_types=1);

/**
 * Verify a FastPix webhook signature before trusting the payload.
 *
 * FastPix signs the raw request body with your webhook Signing Secret
 * (Dashboard > Webhooks) and sends it as a Base64 HMAC-SHA256 in the
 * "FastPix-Signature" header. The Signing Secret is itself Base64-encoded, so
 * sign with its decoded bytes as the key. Verify the body exactly as received:
 * parsing and re-serializing changes the bytes and the signature won't match.
 *
 * Runs offline (no credentials needed): it self-signs a demo payload and checks it.
 * Run: php verify-webhook.php
 */

function isValidSignature(string $rawBody, string $signature, string $secret): bool
{
    if ($secret === '' || $signature === '') {
        return false;
    }
    $key = base64_decode($secret, true); // Signing Secret is Base64; use its decoded bytes.
    if ($key === false) {
        return false;
    }
    $expected = base64_encode(hash_hmac('sha256', $rawBody, $key, true));

    return hash_equals($expected, $signature); // constant-time compare
}

// --- demo: sign a payload the way FastPix does, then verify it ---
// Runs only when this file is executed directly, so it can also be included in tests.
if (isset($argv[0]) && realpath($argv[0]) === realpath(__FILE__)) {
    $secret = getenv('FASTPIX_WEBHOOK_SECRET') ?: base64_encode('demo-secret');
    $rawBody = '{"type":"video.media.ready","data":{"id":"abc-123"}}';
    $signature = base64_encode(hash_hmac('sha256', $rawBody, base64_decode($secret, true), true));

    echo isValidSignature($rawBody, $signature, $secret) ? "verified\n" : "rejected\n";
}

// In a real endpoint you'd read the raw body and header instead:
//   $rawBody   = file_get_contents('php://input');
//   $signature = $_SERVER['HTTP_FASTPIX_SIGNATURE'] ?? '';
//   $secret    = getenv('FASTPIX_WEBHOOK_SECRET');
