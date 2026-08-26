<?php

declare(strict_types=1);

// Mint a signed direct-upload URL. Your client PUTs the file straight to that
// URL, so the bytes never touch your server. Run: php create-upload.php

require __DIR__ . '/../vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;
use FastPix\Sdk\Models\Operations;

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(
        new Components\Security(
            username: getenv('FASTPIX_USERNAME') ?: '',
            password: getenv('FASTPIX_PASSWORD') ?: '',
        )
    )
    ->build();

$response = $sdk->inputVideo->directUploadVideoMedia(
    request: new Operations\DirectUploadVideoMediaRequest(
        // corsOrigin "*" lets a browser PUT from anywhere — lock this down before you ship.
        corsOrigin: '*',
        pushMediaSettings: new Operations\PushMediaSettings(
            metadata: ['key1' => 'value1'],
        ),
    ),
);

$upload = $response->object->data ?? null;
if ($upload === null) {
    fwrite(STDERR, (string) $response->rawResponse->getBody() . "\n");
    exit(1);
}

echo "uploadId: {$upload->uploadId}\n";
echo "url:      {$upload->url}\n\n";

// Send the file straight to the signed URL. We keep this simple and PUT the whole
// file in one request — fine for small files. For larger ones you'll usually want
// a resumable upload; the same signed URL supports that too.
//   curl -X PUT --upload-file video.mp4 -H "Content-Type: video/mp4" "<url>"
echo "PUT your file to the url above, e.g.:\n";
echo "  curl -X PUT --upload-file video.mp4 -H 'Content-Type: video/mp4' \"{$upload->url}\"\n";
