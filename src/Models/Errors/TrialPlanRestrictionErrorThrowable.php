<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Errors;

class TrialPlanRestrictionErrorThrowable extends \RuntimeException
{
    public TrialPlanRestrictionError $container;

    public function __construct(string $message, int $statusCode, TrialPlanRestrictionError $container)
    {
        parent::__construct($message, $statusCode);
        $this->container = $container;
    }
}