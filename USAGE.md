<!-- Start SDK Example Usage [usage] -->
```php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

$sdk = FastPix\Sdk\SDK::builder()
    ->setSecurity(
        new Components\Security(
            username: 'your-access-token',
            password: 'your-secret-key',
        )
    )
    ->build();

$request = new Components\CreateMediaRequest(
    inputs: [
        new Components\VideoInput(
            type: 'video',
            url: 'https://static.fastpix.io/sample.mp4',
        ),
    ],
    metadata: [
        'key1' => 'value1',
    ],
    accessPolicy: Components\CreateMediaRequestAccessPolicy::Public,
);

$response = $sdk->inputVideo->createMedia(
    request: $request
);

if ($response->createMediaSuccessResponse !== null) {
    // handle response
}
```
<!-- End SDK Example Usage [usage] -->