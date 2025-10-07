<?php




declare(strict_types=1);

namespace FastPix\Sdk\Hooks;

class AfterSuccessContext extends HookContext
{
    public function __construct(HookContext $hookCtx)
    {
        parent::__construct($hookCtx->config, $hookCtx->baseURL, $hookCtx->operationID, $hookCtx->oauth2Scopes, $hookCtx->securitySource);
    }
}
