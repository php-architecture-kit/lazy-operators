<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Calendar;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\DateTimeSubFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\IntervalLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\MonthOverflow;
use PHPUnit\Framework\TestCase;

final class DateTimeSubFunctionTest extends TestCase
{
    public function testAMonthBeforeTheThirtyFirstOfMarchIsClippedToFebruary(): void
    {
        $sub = new DateTimeSubFunction(new DateTimeLiteral('2026-03-31T10:00:00+02:00', 'Europe/Warsaw'), new IntervalLiteral('P1M'));

        self::assertSame('2026-02-28T10:00:00+01:00', $sub()->format(DATE_ATOM));
    }

    public function testOverflowKeepsPhpsNativeResult(): void
    {
        $sub = new DateTimeSubFunction(
            new DateTimeLiteral('2026-03-31T10:00:00+02:00', 'Europe/Warsaw'),
            new IntervalLiteral('P1M'),
            MonthOverflow::Overflow,
        );

        self::assertSame('2026-03-03', $sub()->format('Y-m-d'));
    }

    public function testTimeIsSubtractedAsElapsedTime(): void
    {
        // 03:15 +01:00 is 02:15 UTC; 90 minutes earlier is 00:45 UTC, still on summer time.
        $sub = new DateTimeSubFunction(new DateTimeLiteral('2026-10-25T03:15:00+01:00', 'Europe/Warsaw'), new IntervalLiteral('PT1H30M'));

        self::assertSame('2026-10-25T02:45:00+02:00', $sub()->format(DATE_ATOM));
    }
}
