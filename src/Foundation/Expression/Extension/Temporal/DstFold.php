<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal;

/**
 * Which occurrence to take of a wall-clock time the clocks show twice when daylight saving time
 * ends (02:30 on 25 October 2026 in Europe/Warsaw).
 */
enum DstFold
{
    /** The first occurrence, still on summer time (+02:00 in Warsaw). */
    case Earlier;
    /** The second occurrence, already on standard time (+01:00 in Warsaw); PHP's native choice. */
    case Later;
}
