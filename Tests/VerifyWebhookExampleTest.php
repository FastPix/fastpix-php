<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../examples/verify-webhook.php';

final class VerifyWebhookExampleTest extends TestCase
{
    private string $secret;
    private string $rawBody;
    private string $signature;

    protected function setUp(): void
    {
        // Signing secret is Base64, exactly as FastPix stores it.
        $this->secret = base64_encode('a-test-signing-secret');
        $this->rawBody = '{"type":"video.media.ready","data":{"id":"abc-123"}}';
        $this->signature = base64_encode(
            hash_hmac('sha256', $this->rawBody, base64_decode($this->secret, true), true)
        );
    }

    public function testAcceptsValidSignature(): void
    {
        $this->assertTrue(isValidSignature($this->rawBody, $this->signature, $this->secret));
    }

    public function testRejectsWrongSignature(): void
    {
        $this->assertFalse(isValidSignature($this->rawBody, 'not-the-signature', $this->secret));
    }

    public function testRejectsTamperedBody(): void
    {
        $tampered = str_replace('abc-123', 'evil-999', $this->rawBody);
        $this->assertFalse(isValidSignature($tampered, $this->signature, $this->secret));
    }

    public function testRejectsEmptySecretOrSignature(): void
    {
        $this->assertFalse(isValidSignature($this->rawBody, $this->signature, ''));
        $this->assertFalse(isValidSignature($this->rawBody, '', $this->secret));
    }
}
