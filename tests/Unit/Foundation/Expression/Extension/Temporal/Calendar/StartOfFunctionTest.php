<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Calendar;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\CalendarUnit;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\StartOfFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StartOfFunctionTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, CalendarUnit, string}>
     */
    public static function starts(): iterable
    {
        yield 'day' => ['2026-10-17T15:30:00+02:00', 'Europe/Warsaw', CalendarUnit::Day, '2026-10-17T00:00:00+02:00'];
        yield 'day, read in the zone' => ['2026-10-17T23:30:00Z', 'Europe/Warsaw', CalendarUnit::Day, '2026-10-18T00:00:00+02:00'];
        yield 'week, from a Sunday' => ['2026-10-18T15:30:00+02:00', 'Europe/Warsaw', CalendarUnit::Week, '2026-10-12T00:00:00+02:00'];
        yield 'week, from a Monday' => ['2026-10-12T15:30:00+02:00', 'Europe/Warsaw', CalendarUnit::Week, '2026-10-12T00:00:00+02:00'];
        yield 'month' => ['2026-10-17T15:30:00+02:00', 'Europe/Warsaw', CalendarUnit::Month, '2026-10-01T00:00:00+02:00'];
        yield 'year' => ['2026-10-17T15:30:00+02:00', 'Europe/Warsaw', CalendarUnit::Year, '2026-01-01T00:00:00+01:00'];
        yield 'a day whose midnight is skipped' => ['2026-09-06T12:00:00-03:00', 'America/Santiago', CalendarUnit::Day, '2026-09-06T01:00:00-03:00'];
        yield 'a day whose midnight is repeated' => ['2026-11-01T12:00:00-05:00', 'America/Havana', CalendarUnit::Day, '2026-11-01T00:00:00-04:00'];
    }

    #[DataProvider('starts')]
    public function testItReturnsTheFirstRealInstant(string $value, string $timezone, CalendarUnit $unit, string $expected): void
    {
        self::assertSame($expected, (new StartOfFunction(new DateTimeLiteral($value, $timezone), $unit))()->format(DATE_ATOM));
    }
}
