<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Calendar;

use DateInterval;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\DateTimeAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Cast\IntervalCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\IntervalLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\MonthOverflow;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DateTimeAddFunctionTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, MonthOverflow, string}>
     */
    public static function additions(): iterable
    {
        yield 'a calendar day across the clock change' => ['2026-10-24T12:00:00+02:00', 'P1D', MonthOverflow::Clip, '2026-10-25T12:00:00+01:00'];
        yield '24 hours across the clock change' => ['2026-10-24T12:00:00+02:00', 'PT24H', MonthOverflow::Clip, '2026-10-25T11:00:00+01:00'];
        yield 'a month from 31 January, clipped' => ['2026-01-31T10:00:00+01:00', 'P1M', MonthOverflow::Clip, '2026-02-28T10:00:00+01:00'];
        yield 'a month from 31 January, overflowing' => ['2026-01-31T10:00:00+01:00', 'P1M', MonthOverflow::Overflow, '2026-03-03T10:00:00+01:00'];
        yield 'a year from 29 February, clipped' => ['2028-02-29T10:00:00+01:00', 'P1Y', MonthOverflow::Clip, '2029-02-28T10:00:00+01:00'];
        yield 'a month and a day, clipped' => ['2026-01-31T10:00:00+01:00', 'P1M1D', MonthOverflow::Clip, '2026-03-01T10:00:00+01:00'];
        yield 'months and time' => ['2026-01-15T10:00:00+01:00', 'P2MT30M', MonthOverflow::Clip, '2026-03-15T10:30:00+01:00'];
        yield 'a month that fits, either way' => ['2026-01-15T10:00:00+01:00', 'P1M', MonthOverflow::Overflow, '2026-02-15T10:00:00+01:00'];
    }

    #[DataProvider('additions')]
    public function testAddsTheIntervalInTheDateTimesTimezone(string $start, string $interval, MonthOverflow $overflow, string $expected): void
    {
        $add = new DateTimeAddFunction(new DateTimeLiteral($start, 'Europe/Warsaw'), new IntervalLiteral($interval), $overflow);

        self::assertSame($expected, $add()->format(DATE_ATOM));
    }

    public function testClipIsTheDefault(): void
    {
        $add = new DateTimeAddFunction(new DateTimeLiteral('2026-03-31T10:00:00+02:00', 'Europe/Warsaw'), new IntervalLiteral('P1M'));

        self::assertSame('2026-04-30', $add()->format('Y-m-d'));
    }

    public function testAnInvertedIntervalMovesBack(): void
    {
        $interval = new DateInterval('P1M');
        $interval->invert = 1;

        $add = new DateTimeAddFunction(
            new DateTimeLiteral('2026-03-31T10:00:00+02:00', 'Europe/Warsaw'),
            new IntervalCast(new SpyExpression($interval)),
        );

        self::assertSame('2026-02-28', $add()->format('Y-m-d'));
    }
}
