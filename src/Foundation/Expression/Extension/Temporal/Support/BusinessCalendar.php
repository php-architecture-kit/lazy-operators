<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;

/**
 * Weekend days and holidays, read from the data passed to the business-day nodes. A day is a
 * business day when it is neither; both are read in the zone of the date-time being checked.
 *
 * @internal
 */
final readonly class BusinessCalendar
{
    private const DEFAULT_WEEKEND = [6, 7];

    /**
     * @param array<string, true> $holidays
     * @param list<int> $weekend
     */
    private function __construct(
        private array $holidays,
        private array $weekend,
    ) {}

    /**
     * @param array<mixed> $holidays "YYYY-MM-DD" dates
     * @param array<mixed>|null $weekend ISO days of the week, 1 = Monday ... 7 = Sunday; Saturday and Sunday when null
     */
    public static function from(array $holidays, ?array $weekend): self
    {
        $dates = [];

        foreach ($holidays as $holiday) {
            $dates[DateTimes::date($holiday)] = true;
        }

        $days = [];

        foreach ($weekend ?? self::DEFAULT_WEEKEND as $day) {
            if (!is_int($day) || $day < 1 || $day > 7) {
                throw InvalidDateTimeException::invalidWeekday($day);
            }

            $days[$day] = $day;
        }

        if (count($days) === 7) {
            throw InvalidDateTimeException::noBusinessDays();
        }

        return new self($dates, array_values($days));
    }

    public function isBusinessDay(DateTimeImmutable $dateTime): bool
    {
        return !in_array((int) $dateTime->format('N'), $this->weekend, true)
            && !isset($this->holidays[$dateTime->format('Y-m-d')]);
    }

    /**
     * Moves by whole days, counting only business days; the starting day itself never counts.
     * Zero returns the date-time unchanged, a negative count moves back. The wall-clock time is kept.
     */
    public function addBusinessDays(DateTimeImmutable $dateTime, int $days): DateTimeImmutable
    {
        $step = $days > 0 ? '+1 day' : '-1 day';

        for ($remaining = abs($days); $remaining > 0;) {
            $dateTime = $dateTime->modify($step);

            if ($this->isBusinessDay($dateTime)) {
                --$remaining;
            }
        }

        return $dateTime;
    }

    /**
     * The inverse of addBusinessDays(): the business days in (from, to] when moving forward,
     * negated business days in [to, from) when moving back. Days are compared in $from's zone.
     */
    public function businessDaysBetween(DateTimeImmutable $from, DateTimeImmutable $to): int
    {
        $target = $to->setTimezone($from->getTimezone())->format('Y-m-d');
        $cursor = $from->setTime(12, 0);
        $forward = $target > $cursor->format('Y-m-d');
        $count = 0;

        if ($forward) {
            while ($cursor->format('Y-m-d') < $target) {
                $cursor = $cursor->modify('+1 day');
                $count += $this->isBusinessDay($cursor) ? 1 : 0;
            }

            return $count;
        }

        while ($cursor->format('Y-m-d') > $target) {
            $cursor = $cursor->modify('-1 day');
            $count -= $this->isBusinessDay($cursor) ? 1 : 0;
        }

        return $count;
    }
}
