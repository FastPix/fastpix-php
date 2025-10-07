<?php




declare(strict_types=1);

namespace FastPix\Sdk\Hooks;

use FastPix\Sdk\SDKConfiguration;

interface SDKInitHook
{
    public function sdkInit(SDKConfiguration $config): SDKConfiguration;
}
