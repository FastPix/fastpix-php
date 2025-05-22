<!-- Start SDK Example Usage [usage] -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\FastPix::builder()
    ->setSecurity(
        new Components\Security(
            username: '',
            password: '',
        )
    )
    ->build();

$request = new Components\CreateLiveStreamRequest(
    playbackSettings: new Components\PlaybackSettings(),
    inputMediaSettings: new Components\InputMediaSettings(
        metadata: new Components\CreateLiveStreamRequestMetadata(),
    ),
);

$response = $sdk->startLiveStream->createNewStream(
    request: $request
);

if ($response->liveStreamResponseDTO !== null) {
    // handle response
}
```
<!-- End SDK Example Usage [usage] -->