<?php




declare(strict_types=1);

namespace FastPix\Sdk\Hooks;

use Psr\Http\Message\ResponseInterface;

interface AfterErrorHook
{
    public function afterError(AfterErrorContext $context, ?ResponseInterface $response, \Throwable $exception): ErrorResponseContext;
}
