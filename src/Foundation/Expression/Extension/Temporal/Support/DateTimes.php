<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support;

use DateInterval;
use DateInvalidTimeZoneException;
use DateMalformedIntervalStringException;
use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;

/**
 * Reads date-times, zones, intervals and times of day from native values, turning PHP's own
 * exceptions into InvalidDateTimeException.
 *
 * @internal
 */
final class DateTimes
{
    private const TIME_PATTERN = '/^(\d{1,2}):(\d{2})(?::(\d{2}))?$/';
    private const DATE_PATTERN = '/^(\d{4})-(\d{2})-(\d{2})$/';

    public static function zone(string $timezone): DateTimeZone
    {
        try {
            return new DateTimeZone($timezone);
        } catch (DateInvalidTimeZoneException $exception) {
            throw InvalidDateTimeException::unknownTimezone($timezone, $exception);
        }
    }

    /**
     * Accepts a DateTimeInterface, an ISO 8601 string with an offset (or zone), or a Unix timestamp
     * (an int, or a string such as "@1792245600"), and returns it in the given zone.
     */
    public static function read(mixed $value, DateTimeZone $zone): DateTimeImmutable
    {
        $dateTime = match (true) {
            $value instanceof DateTimeInterface => DateTimeImmutable::createFromInterface($value),
            is_int($value) => new DateTimeImmutable('@' . $value),
            is_string($value) && preg_match('/^@-?\d+$/', $value) === 1 => new DateTimeImmutable($value),
            is_string($value) => self::parse($value),
            default => throw InvalidDateTimeException::unsupportedValue($value),
        };

        return $dateTime->setTimezone($zone);
    }

    public static function modify(DateTimeImmutable $dateTime, string $modifier): DateTimeImmutable
    {
        try {
            return $dateTime->modify($modifier);
        } catch (DateMalformedStringException $exception) {
            throw InvalidDateTimeException::malformedModifier($modifier, $exception);
        }
    }

    /**
     * Accepts a DateInterval (returned as a copy, since the native class is mutable) or an
     * ISO 8601 interval string such as "P7D" or "PT30M".
     */
    public static function interval(mixed $value): DateInterval
    {
        if ($value instanceof DateInterval) {
            return clone $value;
        }

        if (!is_string($value)) {
            throw InvalidDateTimeException::unsupportedInterval($value);
        }

        try {
            return new DateInterval($value);
        } catch (DateMalformedIntervalStringException $exception) {
            throw InvalidDateTimeException::malformedInterval($value, $exception);
        }
    }

    /**
     * @return array{int, int, int} hour, minute, second
     */
    public static function time(string $value): array
    {
        if (preg_match(self::TIME_PATTERN, $value, $matches) !== 1) {
            throw InvalidDateTimeException::malformedTime($value);
        }

        [$hour, $minute, $second] = [(int) $matches[1], (int) $matches[2], (int) ($matches[3] ?? 0)];

        if ($hour > 23 || $minute > 59 || $second > 59) {
            throw InvalidDateTimeException::malformedTime($value);
        }

        return [$hour, $minute, $second];
    }

    public static function secondOfDay(string $time): int
    {
        [$hour, $minute, $second] = self::time($time);

        return $hour * 3600 + $minute * 60 + $second;
    }

    public static function normalizeTime(string $time): string
    {
        return sprintf('%02d:%02d:%02d', ...self::time($time));
    }

    /**
     * Validates a "YYYY-MM-DD" calendar date and returns it unchanged.
     */
    public static function date(mixed $value): string
    {
        if (!is_string($value)
            || preg_match(self::DATE_PATTERN, $value, $matches) !== 1
            || !checkdate((int) $matches[2], (int) $matches[3], (int) $matches[1])
        ) {
            throw InvalidDateTimeException::malformedDate(is_string($value) ? $value : get_debug_type($value));
        }

        return $value;
    }

    private static function parse(string $value): DateTimeImmutable
    {
        $parsed = date_parse($value);

        if ($parsed['error_count'] > 0) {
            throw InvalidDateTimeException::malformedDateTime($value);
        }

        if (isset($parsed['relative']) || $parsed['year'] === false || $parsed['month'] === false || $parsed['day'] === false) {
            throw InvalidDateTimeException::relativeDateTime($value);
        }

        if (!isset($parsed['zone_type'])) {
            throw InvalidDateTimeException::missingOffset($value);
        }

        try {
            return new DateTimeImmutable($value);
        } catch (DateMalformedStringException $exception) {
            throw InvalidDateTimeException::malformedDateTime($value, $exception);
        }
    }
}
