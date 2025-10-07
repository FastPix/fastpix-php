<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Operations;


/** The list of value can be order in two ways DESC (Descending) or ASC (Ascending). In case not specified, by default it will be DESC. */
enum OrderBy: string
{
    case Asc = 'asc';
    case Desc = 'desc';
}
