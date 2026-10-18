<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Business;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Business\BusinessDayIsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Business\BusinessDaysAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Business\BusinessDaysBetweenFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\ArrayLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The three business-day nodes share one calendar (Support\BusinessCalendar), so one file covers
 * them and checks that Business Days Between undoes Add Business Days.
 */
final class BusinessDaysTest extends TestCase
{
    private const POLISH_WINTER_HOLIDAYS = ['2026-12-24', '2026-12-25', '2026-12-26', '2027-01-01', '2027-01-06'];

    /**
     * @return iterable<string, array{string, int, list<string>, list<int>|null, string}>
     */
    public static function additions(): iterable
    {
        yield 'Friday + 7' => ['2026-10-16', 7, [], null, '2026-10-27'];
        yield 'Friday + 1 is Monday' => ['2026-10-16', 1, [], null, '2026-10-19'];
        yield 'Saturday + 1 is Monday' => ['2026-10-17', 1, [], null, '2026-10-19'];
        yield 'Saturday + 0 stays on Saturday' => ['2026-10-17', 0, [], null, '2026-10-17'];
        yield 'Monday - 1 is Friday' => ['2026-10-19', -1, [], null, '2026-10-16'];
        yield 'Sunday - 1 is Friday' => ['2026-10-18', -1, [], null, '2026-10-16'];
        yield 'over Christmas and New Year' => ['2026-12-23', 7, self::POLISH_WINTER_HOLIDAYS, null, '2027-01-07'];
        yield 'Friday and Saturday weekend' => ['2026-10-15', 1, [], [5, 6], '2026-10-18'];
    }

    /**
     * @param list<string> $holidays
     * @param list<int>|null $weekend
     */
    #[DataProvider('additions')]
    public function testAddMovesByBusinessDaysAndBetweenUndoesIt(string $start, int $days, array $holidays, ?array $weekend, string $expected): void
    {
        $from = self::at($start);
        $weekendNode = $weekend === null ? null : new ArrayLiteral($weekend);

        $result = (new BusinessDaysAddFunction($from, new IntLiteral($days), new ArrayLiteral($holidays), $weekendNode))();

        self::assertSame($expected . 'T10:00:00', $result->format('Y-m-d\TH:i:s'));
        self::assertSame(
            $days,
            (new BusinessDaysBetweenFunction($from, self::at($expected), new ArrayLiteral($holidays), $weekendNode))(),
        );
    }

    public function testIsBusinessDay(): void
    {
        $holidays = new ArrayLiteral(self::POLISH_WINTER_HOLIDAYS);

        self::assertTrue((new BusinessDayIsFunction(self::at('2026-12-23'), $holidays))());
        self::assertFalse((new BusinessDayIsFunction(self::at('2026-12-24'), $holidays))());
        self::assertFalse((new BusinessDayIsFunction(self::at('2026-12-27'), $holidays))());
        self::assertTrue((new BusinessDayIsFunction(self::at('2026-12-27'), $holidays, new ArrayLiteral([5, 6])))());
    }

    public function testTheDayIsReadInTheDateTimesTimezone(): void
    {
        // 23:30 UTC on Friday is already Saturday in Warsaw.
        $friday = new DateTimeLiteral('2026-10-16T23:30:00Z', 'Europe/Warsaw');

        self::assertFalse((new BusinessDayIsFunction($friday, new ArrayLiteral([])))());
    }

    public function testBetweenTheSameDayIsZero(): void
    {
        self::assertSame(0, (new BusinessDaysBetweenFunction(self::at('2026-10-16'), self::at('2026-10-16'), new ArrayLiteral([])))());
    }

    /**
     * @return iterable<string, array{list<mixed>, list<mixed>|null}>
     */
    public static function invalidCalendars(): iterable
    {
        yield 'malformed holiday' => [['24.12.2026'], null];
        yield 'impossible holiday' => [['2026-02-30'], null];
        yield 'weekday out of range' => [[], [0]];
        yield 'weekday as a string' => [[], ['6']];
        yield 'no business days' => [[], [1, 2, 3, 4, 5, 6, 7]];
    }

    /**
     * @param list<mixed> $holidays
     * @param list<mixed>|null $weekend
     */
    #[DataProvider('invalidCalendars')]
    public function testAnInvalidCalendarIsRejected(array $holidays, ?array $weekend): void
    {
        $this->expectException(InvalidDateTimeException::class);

        (new BusinessDayIsFunction(self::at('2026-10-16'), new ArrayLiteral($holidays), $weekend === null ? null : new ArrayLiteral($weekend)))();
    }

    private static function at(string $date): DateTimeLiteral
    {
        return new DateTimeLiteral($date . 'T10:00:00Z', 'UTC');
    }
}
