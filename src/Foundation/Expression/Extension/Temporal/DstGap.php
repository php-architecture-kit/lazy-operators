<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal;

/**
 * What to do with a wall-clock time the clocks skip when daylight saving time starts
 * (02:30 on 29 March 2026 in Europe/Warsaw).
 */
enum DstGap
{
    /** PHP's native behaviour, move forward by the length of the gap: 02:30 becomes 03:30. */
    case Forward;
    /** Throw NonExistentLocalTimeException. */
    case Reject;
}
