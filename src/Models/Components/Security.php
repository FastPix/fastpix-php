<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;

use FastPix\Sdk\Utils\FastPixMetadata;
class Security
{
    /**
     *
     * @var string $username
     */
    #[FastPixMetadata('security:scheme=true,type=http,subtype=basic,name=username')]
    public string $username;

    /**
     *
     * @var string $password
     */
    #[FastPixMetadata('security:scheme=true,type=http,subtype=basic,name=password')]
    public string $password;

    /**
     * @param  string  $username
     * @param  string  $password
     */
    public function __construct(string $username, string $password)
    {
        // Validate credentials using the validation utility
        [$validatedUsername, $validatedPassword] = \FastPix\Sdk\Utils\Validation::validateCredentials($username, $password);

        $this->username = $validatedUsername;
        $this->password = $validatedPassword;
    }

    /**
     * Create Security instance from environment variables
     */
    public static function fromEnvironment(): self
    {
        $username = $_ENV['FASTPIX_ACCESS_TOKEN'] ?? '';
        $password = $_ENV['FASTPIX_SECRET_KEY'] ?? '';

        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException(
                'FASTPIX_ACCESS_TOKEN and FASTPIX_SECRET_KEY environment variables must be set'
            );
        }

        return new self($username, $password);
    }

    /**
     * Create Security instance from config array
     */
    public static function fromConfig(array $config): self
    {
        if (! isset($config['access_token']) || ! isset($config['secret_key'])) {
            throw new \InvalidArgumentException(
                'Config must contain "access_token" and "secret_key" keys'
            );
        }

        return new self($config['access_token'], $config['secret_key']);
    }

    /**
     * Mask credentials for logging (show only first and last 4 characters)
     */
    public function getMaskedCredentials(): array
    {
        return [
            'username' => $this->maskString($this->username),
            'password' => $this->maskString($this->password),
        ];
    }

    /**
     * Mask a string for logging
     */
    private function maskString(string $value): string
    {
        $length = strlen($value);
        if ($length <= 8) {
            return str_repeat('*', $length);
        }

        return substr($value, 0, 4).str_repeat('*', $length - 8).substr($value, -4);
    }
}