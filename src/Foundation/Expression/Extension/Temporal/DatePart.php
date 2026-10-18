<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal;

use DateTimeImmutable;

/**
 * A wall-clock field of a date-time, read in the zone the date-time carries.
 */
enum DatePart
{
    case Year;
    case Month;
    case Day;
    case Hour;
    case Minute;
    case Second;
    /** ISO 8601: 1 = Monday ... 7 = Sunday. */
    case DayOfWeek;
    /** 1 ... 366. */
    case DayOfYear;
    /** ISO 8601 week number, 1 ... 53. */
    case IsoWeek;
    /**
     * Seconds since the wall clock showed 00:00:00, read from the fields, so 12:00 is 43200
     * even on a 23- or 25-hour day.
     */
    case SecondOfDay;

    public function read(DateTimeImmutable $dateTime): int
    {
        return match ($this) {
            self::Year => (int) $dateTime->format('Y'),
            self::Month => (int) $dateTime->format('n'),
            self::Day => (int) $dateTime->format('j'),
            self::Hour => (int) $dateTime->format('G'),
            self::Minute => (int) $dateTime->format('i'),
            self::Second => (int) $dateTime->format('s'),
            self::DayOfWeek => (int) $dateTime->format('N'),
            self::DayOfYear => (int) $dateTime->format('z') + 1,
            self::IsoWeek => (int) $dateTime->format('W'),
            self::SecondOfDay => (int) $dateTime->format('G') * 3600
                + (int) $dateTime->format('i') * 60
                + (int) $dateTime->format('s'),
        };
    }
}
