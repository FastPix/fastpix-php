<?php



declare(strict_types=1);

namespace FastPix\Sdk\Models\Components;


/** The values in the list can be arranged in two ways: DESC (Descending) or ASC (Ascending). */
enum SortOrder: string
{
    case Asc = 'asc';
    case Desc = 'desc';
}
