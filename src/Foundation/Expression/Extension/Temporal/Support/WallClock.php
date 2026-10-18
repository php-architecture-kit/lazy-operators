<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\CalendarUnit;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DstFold;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DstGap;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\NonExistentLocalTimeException;

/**
 * Turns a wall-clock time on a given day into a point in time, with an explicit policy for the
 * times daylight saving makes skipped or repeated. Everything goes through DateTimeImmutable;
 * the only extra step is looking a few hours either side for a second occurrence of the time.
 *
 * @internal
 */
final class WallClock
{
    /** Longest clock change to look for on either side of a time (historic double summer time was 2 hours). */
    private const SEARCH_WINDOW = 3 * 3600;

    public static function at(DateTimeImmutable $day, int $hour, int $minute, int $second, DstGap $gap, DstFold $fold): DateTimeImmutable
    {
        $candidate = $day->setTime($hour, $minute, $second);

        if (self::fields($candidate) !== [$day->format('Y-m-d'), $hour, $minute, $second]) {
            if ($gap === DstGap::Reject) {
                throw NonExistentLocalTimeException::create(
                    $day->format('Y-m-d'),
                    sprintf('%02d:%02d:%02d', $hour, $minute, $second),
                    $day->getTimezone()->getName(),
                );
            }

            return $candidate;
        }

        $other = $fold === DstFold::Earlier ? self::earlierOccurrence($candidate) : self::laterOccurrence($candidate);

        return $other ?? $candidate;
    }

    /**
     * The first instant of the day, week (Monday), month or year the date-time falls in.
     */
    public static function startOf(DateTimeImmutable $dateTime, CalendarUnit $unit): DateTimeImmutable
    {
        $day = match ($unit) {
            CalendarUnit::Day => $dateTime,
            CalendarUnit::Week => $dateTime->modify(sprintf('-%d days', (int) $dateTime->format('N') - 1)),
            CalendarUnit::Month => $dateTime->setDate((int) $dateTime->format('Y'), (int) $dateTime->format('n'), 1),
            CalendarUnit::Year => $dateTime->setDate((int) $dateTime->format('Y'), 1, 1),
        };

        return self::at($day, 0, 0, 0, DstGap::Forward, DstFold::Earlier);
    }

    private static function earlierOccurrence(DateTimeImmutable $dateTime): ?DateTimeImmutable
    {
        $timestamp = $dateTime->getTimestamp();
        $shift = self::offsetAt($dateTime, $timestamp - self::SEARCH_WINDOW) - $dateTime->getOffset();

        return self::sameWallClock($dateTime, $timestamp - $shift, $shift);
    }

    private static function laterOccurrence(DateTimeImmutable $dateTime): ?DateTimeImmutable
    {
        $timestamp = $dateTime->getTimestamp();
        $shift = $dateTime->getOffset() - self::offsetAt($dateTime, $timestamp + self::SEARCH_WINDOW);

        return self::sameWallClock($dateTime, $timestamp + $shift, $shift);
    }

    private static function sameWallClock(DateTimeImmutable $dateTime, int $timestamp, int $shift): ?DateTimeImmutable
    {
        if ($shift <= 0) {
            return null;
        }

        $other = $dateTime->setTimestamp($timestamp);

        return self::fields($other) === self::fields($dateTime) ? $other : null;
    }

    private static function offsetAt(DateTimeImmutable $dateTime, int $timestamp): int
    {
        return $dateTime->setTimestamp($timestamp)->getOffset();
    }

    /**
     * @return array{string, int, int, int}
     */
    private static function fields(DateTimeImmutable $dateTime): array
    {
        return [
            $dateTime->format('Y-m-d'),
            (int) $dateTime->format('G'),
            (int) $dateTime->format('i'),
            (int) $dateTime->format('s'),
        ];
    }
}
