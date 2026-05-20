# SigningKeys

## Overview

Operations involving signing keys

### Available Operations

* [createSigningKey](#createsigningkey) - Create a signing key
* [listSigningKeys](#listsigningkeys) - Get list of signing key
* [deleteSigningKey](#deletesigningkey) - Delete a signing key
* [getSigningKeyById](#getsigningkeybyid) - Get signing key by ID

## createSigningKey

This endpoint allows you to create a new signing key pair for FastPix. When you call this endpoint, the API generates a 2048-bit RSA key pair. The privateKey is returned in the response, encoded in Base64 format. You also receive a unique key ID to reference the key in future operations. FastPix securely stores the public key to validate signed tokens. 


<h4>Instructions</h4> 


**Private key handling:** The privateKey you receive is encoded in Base64. To use it, decode the value using Base64 decoding. Make sure to store this private key securely, as it is required for signing tokens. 


**Key-ID:** The ID is used to reference this specific key pair in future API requests or configurations.


After the key pair is generated, the developer must securely store the private key because FastPix does not save it. The public key is used by FastPix to verify signed tokens and ensure that the client interacting with the system is legitimate.





<h4>Use case scenario</h4> 



**Use case:** A developer building a video subscription service wants to ensure that only authorized users can access premium content. By generating a signing key, the developer can issue signed JSON Web Tokens (JWTs) to authenticate and authorize users. These tokens can be validated by FastPix using the stored public key. 


**Detailed example:**  You are building a video-on-demand platform that restricts access based on user subscriptions. To ensure only subscribed users can stream content, you generate a signing key using this API. Each time a user logs in, you create a JWT signed with the private key. When the user attempts to play a video, FastPix uses the public key to verify the token and confirms that the user is authorized.<br/>
Related guide: <a href="https://fastpix.com/docs/video-security/secure-media-access-with-jwts">Create and use signing keys</a>

### Example Usage

<!-- UsageSnippet language="php" operationID="create_signing_key" method="post" path="/iam/signing-keys" -->
```php
<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

try {
    $sdk = Sdk\Fastpixsdk::builder()
        ->setSecurity(
            new Components\Security(
                username: 'your-access-token',
                password: 'your-secret-key',
            )
        )
        ->build();

    $response = $sdk->signingKeys->createSigningKey(

);

    if ($response->statusCode >= 200 && $response->statusCode < 300) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo ($decoded !== null ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $rawBody) . "\n";
    } else {
        $errorPayload = $response->defaultError ?? $response->error ?? null;
        if ($errorPayload !== null) {
            $errorResponse = json_decode(json_encode($errorPayload), true);
            echo json_encode($errorResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        } else {
            echo json_encode(['message' => 'No response data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        }
    }
} catch (\Exception $e) {
    // Extract API error response
    $errorBody = null;
    if (property_exists($e, 'body') && property_exists($e, 'statusCode')) {
        $body = $e->body;
        $errorBody = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorBody = $body;
        }
    } elseif (method_exists($e, 'getResponse')) {
        $response = $e->getResponse();
        if ($response !== null) {
            $body = (string)$response->getBody();
            $errorBody = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorBody = $body;
            }
        }
    }
    
    // Output API error response
    if ($errorBody !== null) {
        echo json_encode($errorBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
    exit(1);
}
```

### Parameters

No parameters required.

### Response

**[?Operations\CreateSigningKeyResponse](../../Models/Operations/CreateSigningKeyResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## listSigningKeys

This endpoint retrieves a list of all signing keys in the workspace. Optionally use `limit` and `offset` for pagination.

### Example Usage

<!-- UsageSnippet language="php" operationID="list-signing-keys" method="get" path="/iam/signing-keys" -->
```php
<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

try {
    $sdk = Sdk\Fastpixsdk::builder()
        ->setSecurity(
            new Components\Security(
                username: 'your-access-token',
                password: 'your-secret-key',
            )
        )
        ->build();

    $response = $sdk->signingKeys->listSigningKeys(
        limit: 20,
        offset: 0,
    );

    if ($response->statusCode >= 200 && $response->statusCode < 300) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo ($decoded !== null ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $rawBody) . "\n";
    } else {
        $errorPayload = $response->defaultError ?? $response->error ?? null;
        if ($errorPayload !== null) {
            $errorResponse = json_decode(json_encode($errorPayload), true);
            echo json_encode($errorResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        } else {
            echo json_encode(['message' => 'No response data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        }
    }
} catch (\Exception $e) {
    // Extract API error response
    $errorBody = null;
    if (property_exists($e, 'body') && property_exists($e, 'statusCode')) {
        $body = $e->body;
        $errorBody = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorBody = $body;
        }
    } elseif (method_exists($e, 'getResponse')) {
        $response = $e->getResponse();
        if ($response !== null) {
            $body = (string)$response->getBody();
            $errorBody = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorBody = $body;
            }
        }
    }
    
    // Output API error response
    if ($errorBody !== null) {
        echo json_encode($errorBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
    exit(1);
}
```

### Parameters

| Parameter                                                                                                               | Type                                                                                                                    | Required                                                                                                                | Description                                                                                                             |
| ----------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------- |
| `limit`                                                                                                                 | *?int*                                                                                                                 | :heavy_minus_sign:                                                                                                     | Limit specifies the maximum number of items to display per page.                                                        |
| `offset`                                                                                                                | *?int*                                                                                                                 | :heavy_minus_sign:                                                                                                     | Offset determines the starting point for data retrieval within a paginated list.                                       |

### Response

**[?Operations\ListSigningKeysResponse](../../Models/Operations/ListSigningKeysResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## deleteSigningKey

This endpoint deletes a signing key by its unique ID. Once deleted, the key can no longer be used for signing or verification.

### Example Usage

<!-- UsageSnippet language="php" operationID="delete-signing-key" method="delete" path="/iam/signing-keys/{signingKeyId}" -->
```php
<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

try {
    $sdk = Sdk\Fastpixsdk::builder()
        ->setSecurity(
            new Components\Security(
                username: 'your-access-token',
                password: 'your-secret-key',
            )
        )
        ->build();

    // The ID of the signing key to delete
    $signingKeyId = 'your-signing-key-id';

    $response = $sdk->signingKeys->deleteSigningKey(
        signingKeyId: $signingKeyId,
    );

    if ($response->statusCode >= 200 && $response->statusCode < 300) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo ($decoded !== null ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $rawBody) . "\n";
    } else {
        $errorPayload = $response->defaultError ?? $response->error ?? null;
        if ($errorPayload !== null) {
            $errorResponse = json_decode(json_encode($errorPayload), true);
            echo json_encode($errorResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        } else {
            echo json_encode(['message' => 'No response data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        }
    }
} catch (\Exception $e) {
    // Extract API error response
    $errorBody = null;
    if (property_exists($e, 'body') && property_exists($e, 'statusCode')) {
        $body = $e->body;
        $errorBody = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorBody = $body;
        }
    } elseif (method_exists($e, 'getResponse')) {
        $response = $e->getResponse();
        if ($response !== null) {
            $body = (string)$response->getBody();
            $errorBody = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorBody = $body;
            }
        }
    }
    
    // Output API error response
    if ($errorBody !== null) {
        echo json_encode($errorBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
    exit(1);
}
```

### Parameters

| Parameter                                                                                                               | Type                                                                                                                    | Required                                                                                                                | Description                                                                                                             | Example                                                                                                                 |
| ----------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------- |
| `signingKeyId`                                                                                                          | *string*                                                                                                                | :heavy_check_mark:                                                                                                      | When creating the signing key, FastPix assigns a universally unique identifier with a maximum length of 255 characters. | 3ta85f64-5717-4562-b3fc-2c963f66afa6                                                                                    |

### Response

**[?Operations\DeleteSigningKeyResponse](../../Models/Operations/DeleteSigningKeyResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\APIException | 4XX, 5XX            | \*/\*               |

## getSigningKeyById

This endpoint allows you to retrieve detailed information about a specific signing key using its unique key id. While the private key is not returned for security reasons, You can view the key’s creation date, status, and other associated metadata. This endpoint also returns the workspaceId and publicKey in the response. 


<h4>Usage: Generating a JWT token</h4> 

In the response, the API returns the workspaceId and publicKey associated with the signing key. With the publicKey and the privateKey obtained from the "Create a Signing Key" endpoint, you can generate a JSON Web Token (JWT) using the RS256 algorithm. This token can be utilized for accessing private media assets, GIFs, thumbnails, and spritesheets. 



<h4>Payload:</h4> 


```
{ 
"kid": "your-signing-key-id",
  "aud": "media:your-media-id",
  "iss": "fastpix.com", 
  "sub": "", 
  "iat": 1706703204, 
  "exp": 1735626783 

} 
```



* **kid:** The key ID of the signing key. 
* **aud:** The audience for which the token is intended, enter the playbackId here.
* **iss:**  The issuer of the token (for example, "fastpix.com "). 
* **sub:** The subject of the token, typically representing the user or entity the token is issued for. In this case, use the workspaceId fetched from the "Get Signing Key by ID" endpoint. 
* **groups:** An array of groups the subject belongs to (for example, ["user"]).
* **iat:** The issued-at timestamp, indicating when the token was created. 
* **exp:** The expiration timestamp, indicating when the token will no longer be valid. 





<h4>Use case scenario</h4> 



**Use case:** A developer is unsure about the status of a signing key they created months ago and wants to verify whether it's still in use or has expired. 



**Detailed example:**  You’re working on a streaming platform and realize you haven’t checked the status of a signing key that was used for playback access several months ago. By fetching the key details using its ID, you can confirm whether it’s still active, when it was created, and if it’s nearing expiration. This allows you to plan a rotation or deactivation if needed.

### Example Usage

<!-- UsageSnippet language="php" operationID="get-signing_key_by_id" method="get" path="/iam/signing-keys/{signingKeyId}" -->
```php
<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use FastPix\Sdk;
use FastPix\Sdk\Models\Components;

try {
    $sdk = Sdk\Fastpixsdk::builder()
        ->setSecurity(
            new Components\Security(
                username: 'your-access-token',
                password: 'your-secret-key',
            )
        )
        ->build();

    $response = $sdk->signingKeys->getSigningKeyById(
    signingKeyId: '5ta85f64-5717-4562-b3fc-2c963f66afa6'
);

    if ($response->statusCode >= 200 && $response->statusCode < 300) {
        $rawBody = (string) $response->rawResponse->getBody();
        $decoded = json_decode($rawBody, true);
        echo ($decoded !== null ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $rawBody) . "\n";
    } else {
        $errorPayload = $response->defaultError ?? $response->error ?? null;
        if ($errorPayload !== null) {
            $errorResponse = json_decode(json_encode($errorPayload), true);
            echo json_encode($errorResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        } else {
            echo json_encode(['message' => 'No response data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        }
    }
} catch (\Exception $e) {
    // Extract API error response
    $errorBody = null;
    if (property_exists($e, 'body') && property_exists($e, 'statusCode')) {
        $body = $e->body;
        $errorBody = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorBody = $body;
        }
    } elseif (method_exists($e, 'getResponse')) {
        $response = $e->getResponse();
        if ($response !== null) {
            $body = (string)$response->getBody();
            $errorBody = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorBody = $body;
            }
        }
    }
    
    // Output API error response
    if ($errorBody !== null) {
        echo json_encode($errorBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
    exit(1);
}
```

### Parameters

| Parameter                                                                                                                | Type                                                                                                                     | Required                                                                                                                 | Description                                                                                                              | Example                                                                                                                  |
| ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------ |
| `signingKeyId`                                                                                                           | *string*                                                                                                                 | :heavy_check_mark:                                                                                                       | When creating the signing key, FastPix assigns a universally unique identifier with a maximum length of 255 characters.  | 5ta85f64-5717-4562-b3fc-2c963f66afa6                                                                                     |

### Response

**[?Operations\GetSigningKeyByIdResponse](../../Models/Operations/GetSigningKeyByIdResponse.md)**

### Errors

| Error Type          | Status Code         | Content Type        |
| ------------------- | ------------------- | ------------------- |
| Errors\APIException | 4XX, 5XX            | \*/\*               |