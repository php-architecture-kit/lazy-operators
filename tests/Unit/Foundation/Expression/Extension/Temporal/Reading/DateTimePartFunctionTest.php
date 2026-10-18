<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DatePart;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\DateTimePartFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DateTimePartFunctionTest extends TestCase
{
    /**
     * @return iterable<string, array{DatePart, int}>
     */
    public static function parts(): iterable
    {
        // 2026-10-25T12:34:56+01:00, a Sunday with 25 hours in Europe/Warsaw.
        yield 'year' => [DatePart::Year, 2026];
        yield 'month' => [DatePart::Month, 10];
        yield 'day' => [DatePart::Day, 25];
        yield 'hour' => [DatePart::Hour, 12];
        yield 'minute' => [DatePart::Minute, 34];
        yield 'second' => [DatePart::Second, 56];
        yield 'day of week' => [DatePart::DayOfWeek, 7];
        yield 'day of year' => [DatePart::DayOfYear, 298];
        yield 'ISO week' => [DatePart::IsoWeek, 43];
        yield 'second of day, from the wall clock' => [DatePart::SecondOfDay, 45296];
    }

    #[DataProvider('parts')]
    public function testItReadsThePartFromTheWallClock(DatePart $part, int $expected): void
    {
        $value = new DateTimeLiteral('2026-10-25T11:34:56Z', 'Europe/Warsaw');

        self::assertSame($expected, (new DateTimePartFunction($value, $part))());
    }
}
