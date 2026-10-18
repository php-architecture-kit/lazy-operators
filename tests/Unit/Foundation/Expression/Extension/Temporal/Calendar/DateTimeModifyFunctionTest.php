<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Calendar;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\DateTimeModifyFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PHPUnit\Framework\TestCase;

final class DateTimeModifyFunctionTest extends TestCase
{
    public function testCalendarDaysKeepTheWallClockAcrossAClockChange(): void
    {
        $modify = new DateTimeModifyFunction(
            new DateTimeLiteral('2026-10-20T12:00:00+02:00', 'Europe/Warsaw'),
            new StringLiteral('+7 days'),
        );

        self::assertSame('2026-10-27T12:00:00+01:00', $modify()->format(DATE_ATOM));
    }

    public function testWeekdaysSkipTheWeekend(): void
    {
        $modify = new DateTimeModifyFunction(
            new DateTimeLiteral('2026-10-16T10:00:00+02:00', 'Europe/Warsaw'),
            new StringLiteral('+7 weekdays'),
        );

        self::assertSame('2026-10-27', $modify()->format('Y-m-d'));
    }

    public function testMidnightIsTheStartOfTheDayInTheDateTimesTimezone(): void
    {
        $modify = new DateTimeModifyFunction(
            new DateTimeLiteral('2026-10-17T23:30:00Z', 'Europe/Warsaw'),
            new StringLiteral('midnight'),
        );

        self::assertSame('2026-10-18T00:00:00+02:00', $modify()->format(DATE_ATOM));
    }

    public function testAMalformedModifierIsRejected(): void
    {
        $this->expectException(InvalidDateTimeException::class);
        $this->expectExceptionMessage('"in a week" is not a valid date-time modifier.');

        (new DateTimeModifyFunction(
            new DateTimeLiteral('2026-10-17T10:00:00Z', 'UTC'),
            new StringLiteral('in a week'),
        ))();
    }
}
