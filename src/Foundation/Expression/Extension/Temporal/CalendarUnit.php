<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal;

enum CalendarUnit
{
    case Day;
    /** ISO 8601 week, starting on Monday. */
    case Week;
    case Month;
    case Year;
}
