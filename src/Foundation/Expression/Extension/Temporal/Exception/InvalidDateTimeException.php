<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception;

use InvalidArgumentException;
use Throwable;

final class InvalidDateTimeException extends InvalidArgumentException implements LazyOperatorsTemporalException
{
    public static function unknownTimezone(string $timezone, ?Throwable $previous = null): self
    {
        return new self(sprintf('"%s" is not a known timezone identifier.', $timezone), 0, $previous);
    }

    public static function missingOffset(string $value): self
    {
        return new self(sprintf(
            '"%s" has no UTC offset or timezone; write it as e.g. "2026-10-17T10:00:00+02:00" or "...Z".',
            $value,
        ));
    }

    public static function relativeDateTime(string $value): self
    {
        return new self(sprintf(
            '"%s" is relative to the current time; pass the current time through a port instead.',
            $value,
        ));
    }

    public static function malformedDateTime(string $value, ?Throwable $previous = null): self
    {
        return new self(sprintf('"%s" is not a valid date-time.', $value), 0, $previous);
    }

    public static function malformedModifier(string $modifier, ?Throwable $previous = null): self
    {
        return new self(sprintf('"%s" is not a valid date-time modifier.', $modifier), 0, $previous);
    }

    public static function malformedInterval(string $value, ?Throwable $previous = null): self
    {
        return new self(sprintf('"%s" is not a valid ISO 8601 interval, e.g. "P7D" or "PT30M".', $value), 0, $previous);
    }

    public static function malformedTime(string $value): self
    {
        return new self(sprintf('"%s" is not a time of day; expected "HH:MM" or "HH:MM:SS".', $value));
    }

    public static function malformedDate(string $value): self
    {
        return new self(sprintf('"%s" is not a date; expected "YYYY-MM-DD".', $value));
    }

    public static function invalidWeekday(mixed $value): self
    {
        return new self(sprintf('%s is not an ISO day of the week (1 = Monday ... 7 = Sunday).', var_export($value, true)));
    }

    public static function noBusinessDays(): self
    {
        return new self('The weekend covers every day of the week, so there are no business days.');
    }

    public static function unsupportedValue(mixed $value): self
    {
        return new self(sprintf(
            'Cannot read a date-time from %s; expected a DateTimeInterface, an ISO 8601 string with an offset, or a Unix timestamp.',
            get_debug_type($value),
        ));
    }

    public static function unsupportedInterval(mixed $value): self
    {
        return new self(sprintf(
            'Cannot read an interval from %s; expected a DateInterval or an ISO 8601 interval string.',
            get_debug_type($value),
        ));
    }
}
