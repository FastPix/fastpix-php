<?php




declare(strict_types=1);

namespace FastPix\Sdk\Hooks;

class TokenResponse
{
    public ?string $accessToken;
    public ?string $tokenType;
    public ?float $expiresIn;
}
