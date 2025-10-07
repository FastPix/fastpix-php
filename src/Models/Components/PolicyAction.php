<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** Policy action type */
enum PolicyAction: string
{
    case Allow = 'allow';
    case Deny = 'deny';
}
