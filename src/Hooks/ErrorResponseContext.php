<?php




declare(strict_types=1);

namespace FastPix\Sdk\Hooks;
use Psr\Http\Message\ResponseInterface;

class ErrorResponseContext
{
    public ?ResponseInterface $response;
    public ?\Throwable $e;

    public function __construct(?ResponseInterface $response, ?\Throwable $e)
    {
        $this->response = $response;
        $this->e = $e;
    }
}
