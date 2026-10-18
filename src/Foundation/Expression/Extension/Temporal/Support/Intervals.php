<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support;

use DateInterval;
use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\MonthOverflow;

/**
 * @internal
 */
final class Intervals
{
    /**
     * Adds (or, with $subtract, subtracts) an interval. With MonthOverflow::Clip the years and
     * months are applied first from the first of the month and the day is clipped to the target
     * month's length, then the days and the time part are added natively.
     */
    public static function apply(DateTimeImmutable $dateTime, DateInterval $interval, bool $subtract, MonthOverflow $overflow): DateTimeImmutable
    {
        $months = $interval->y * 12 + $interval->m;

        if ($overflow === MonthOverflow::Overflow || $months === 0) {
            return $subtract ? $dateTime->sub($interval) : $dateTime->add($interval);
        }

        $negative = ($interval->invert === 1) !== $subtract;
        $day = (int) $dateTime->format('j');

        $target = $dateTime
            ->setDate((int) $dateTime->format('Y'), (int) $dateTime->format('n'), 1)
            ->modify(sprintf('%s%d months', $negative ? '-' : '+', $months));
        $target = $target->setDate(
            (int) $target->format('Y'),
            (int) $target->format('n'),
            min($day, (int) $target->format('t')),
        );

        $rest = new DateInterval(sprintf('P%dDT%dH%dM%dS', $interval->d, $interval->h, $interval->i, $interval->s));
        $rest->f = $interval->f;
        $rest->invert = $negative ? 1 : 0;

        return $target->add($rest);
    }
}
