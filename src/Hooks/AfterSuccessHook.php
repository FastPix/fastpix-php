<?php




declare(strict_types=1);

namespace FastPix\Sdk\Hooks;

use Psr\Http\Message\ResponseInterface;

interface AfterSuccessHook
{
    public function afterSuccess(AfterSuccessContext $context, ResponseInterface $response): ResponseInterface;
}
