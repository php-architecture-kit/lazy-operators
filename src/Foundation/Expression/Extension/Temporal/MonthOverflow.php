<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal;

/**
 * What adding months does to a day that the target month does not have (31 January + 1 month).
 */
enum MonthOverflow
{
    /** Stay in the target month, on its last day: 31 January + 1 month = 28 February. */
    case Clip;
    /** PHP's native behaviour, the extra days spill into the next month: 31 January + 1 month = 3 March. */
    case Overflow;
}
