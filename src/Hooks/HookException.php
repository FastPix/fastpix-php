<?php

declare(strict_types=1);

namespace FastPix\Sdk\Hooks;

/**
 * Thrown when one of the SDK lifecycle hooks (SDKInit, BeforeRequest,
 * AfterSuccess, AfterError) raises an exception while executing.
 *
 * Provides a specific exception type so callers can distinguish hook
 * failures from other runtime errors instead of catching a generic
 * \Exception.
 */
class HookException extends \Exception
{
}
