<?php




declare(strict_types=1);

namespace FastPix\Sdk\Hooks;

class Credentials
{
    public string $clientID;
    public string $clientSecret;
    public string $tokenURL;

    public function __construct(string $clientID, string $clientSecret, string $tokenURL)
    {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->tokenURL = $tokenURL;
    }
}
