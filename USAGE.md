<!-- Start SDK Example Usage [usage] -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = Sdk\Fastpixsdk::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

$request = new Components\CreateMediaRequest(
    inputs: [
        new Components\PullVideoInput(),
    ],
    metadata: [
        'key1' => 'value1',
    ],
);

$response = $sdk->inputVideo->createMedia(
    request: $request
);

if ($response->createMediaSuccessResponse !== null) {
    // handle response
}
```
<!-- End SDK Example Usage [usage] -->