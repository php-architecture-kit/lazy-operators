<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal;

use DateTimeInterface;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Business\BusinessDayIsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Business\BusinessDaysAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Business\BusinessDaysBetweenFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\DateTimeAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\DateTimeAtTimeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\DateTimeModifyFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\DateTimeSubFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\StartOfFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Cast\DateTimeCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Cast\IntervalCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\IntervalLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\TimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\DateTimeRangeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeAnyContainsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeContainsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeEndFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeIntersectionFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeOverlapsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeStartFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\DateTimeDiffFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\DateTimeFormatFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\DateTimePartFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\DateTimeTimestampFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\IntervalSecondsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\TimeOfDayBetweenFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DecoratedDateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DecoratedIntervalValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DecoratedRangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\IntervalValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

/**
 * Static factories for date-time nodes. The timezone is given once, to datetime(); every later
 * calendar and wall-clock operation runs in the timezone the date-time carries.
 */
class Temporal
{
    use WrapsRawValues;
    use DecoratesNodes;

    /**
     * A raw value (DateTimeInterface, ISO 8601 string with an offset, Unix timestamp) with a raw
     * timezone becomes a literal; anything given as an Expression (a port, say) is cast when the
     * tree is evaluated.
     */
    public static function datetime(
        DateTimeInterface|string|int|Expression $value,
        string|StringValue $timezone,
        ?ExpressionTreeConfig $config = null,
    ): DateTimeValue {
        $raw = match (true) {
            $value instanceof Expression => null,
            $value instanceof DateTimeInterface => $value->format('Y-m-d\TH:i:s.uP'),
            is_int($value) => '@' . $value,
            default => $value,
        };

        $node = match (true) {
            $raw !== null && is_string($timezone) => new DateTimeLiteral($raw, $timezone),
            default => new DateTimeCast(
                $raw === null ? $value : new StringLiteral($raw),
                self::wrapAs(StringValue::class, $timezone),
            ),
        };

        return self::decorateDateTime($node, self::config($config));
    }

    public static function interval(string|Expression $value, ?ExpressionTreeConfig $config = null): IntervalValue
    {
        $node = match (true) {
            is_string($value) => new IntervalLiteral($value),
            $value instanceof IntervalValue => $value,
            default => new IntervalCast($value),
        };

        return self::decorateInterval($node, self::config($config));
    }

    public static function time(string $value, ?ExpressionTreeConfig $config = null): StringValue
    {
        return self::decorateString(new TimeLiteral($value), self::config($config));
    }

    public static function modify(DateTimeValue $datetime, string|StringValue $modifier, ?ExpressionTreeConfig $config = null): DateTimeValue
    {
        return self::decorateDateTime(new DateTimeModifyFunction($datetime, self::wrapAs(StringValue::class, $modifier)), self::config($config));
    }

    public static function add(
        DateTimeValue $datetime,
        string|Expression $interval,
        MonthOverflow $overflow = MonthOverflow::Clip,
        ?ExpressionTreeConfig $config = null,
    ): DateTimeValue {
        return self::decorateDateTime(new DateTimeAddFunction($datetime, self::interval($interval), $overflow), self::config($config));
    }

    public static function sub(
        DateTimeValue $datetime,
        string|Expression $interval,
        MonthOverflow $overflow = MonthOverflow::Clip,
        ?ExpressionTreeConfig $config = null,
    ): DateTimeValue {
        return self::decorateDateTime(new DateTimeSubFunction($datetime, self::interval($interval), $overflow), self::config($config));
    }

    public static function atTime(
        DateTimeValue $datetime,
        string|StringValue $time,
        DstGap $gap = DstGap::Forward,
        DstFold $fold = DstFold::Later,
        ?ExpressionTreeConfig $config = null,
    ): DateTimeValue {
        return self::decorateDateTime(
            new DateTimeAtTimeFunction($datetime, self::timeOf($time), $gap, $fold),
            self::config($config),
        );
    }

    public static function startOf(DateTimeValue $datetime, CalendarUnit $unit, ?ExpressionTreeConfig $config = null): DateTimeValue
    {
        return self::decorateDateTime(new StartOfFunction($datetime, $unit), self::config($config));
    }

    public static function format(DateTimeValue $datetime, string|StringValue $format, ?ExpressionTreeConfig $config = null): StringValue
    {
        return self::decorateString(new DateTimeFormatFunction($datetime, self::wrapAs(StringValue::class, $format)), self::config($config));
    }

    public static function part(DateTimeValue $datetime, DatePart $part, ?ExpressionTreeConfig $config = null): IntegerValue
    {
        return self::decorateInteger(new DateTimePartFunction($datetime, $part), self::config($config));
    }

    public static function timestamp(DateTimeValue $datetime, ?ExpressionTreeConfig $config = null): IntegerValue
    {
        return self::decorateInteger(new DateTimeTimestampFunction($datetime), self::config($config));
    }

    public static function diff(DateTimeValue $from, DateTimeValue $to, ?ExpressionTreeConfig $config = null): IntervalValue
    {
        return self::decorateInterval(new DateTimeDiffFunction($from, $to), self::config($config));
    }

    public static function secondsBetween(DateTimeValue $from, DateTimeValue $to, ?ExpressionTreeConfig $config = null): IntegerValue
    {
        return self::decorateInteger(new IntervalSecondsFunction($from, $to), self::config($config));
    }

    public static function timeOfDayBetween(
        DateTimeValue $datetime,
        string|StringValue $from,
        string|StringValue $to,
        ?ExpressionTreeConfig $config = null,
    ): BooleanValue {
        return self::decorateBoolean(
            new TimeOfDayBetweenFunction($datetime, self::timeOf($from), self::timeOf($to)),
            self::config($config),
        );
    }

    public static function range(DateTimeValue $start, ?DateTimeValue $end = null, ?ExpressionTreeConfig $config = null): RangeValue
    {
        return self::decorateRange(new DateTimeRangeFunction($start, $end), self::config($config));
    }

    public static function contains(RangeValue $range, DateTimeValue $datetime, ?ExpressionTreeConfig $config = null): BooleanValue
    {
        return self::decorateBoolean(new RangeContainsFunction($range, $datetime), self::config($config));
    }

    public static function anyContains(DateTimeValue $datetime, RangeValue $first, RangeValue ...$rest): BooleanValue
    {
        return new RangeAnyContainsFunction($datetime, $first, ...$rest);
    }

    public static function overlaps(RangeValue $left, RangeValue $right, ?ExpressionTreeConfig $config = null): BooleanValue
    {
        return self::decorateBoolean(new RangeOverlapsFunction($left, $right), self::config($config));
    }

    public static function intersection(RangeValue $left, RangeValue $right, ?ExpressionTreeConfig $config = null): RangeValue
    {
        return self::decorateRange(new RangeIntersectionFunction($left, $right), self::config($config));
    }

    public static function rangeStart(RangeValue $range, ?ExpressionTreeConfig $config = null): DateTimeValue
    {
        return self::decorateDateTime(new RangeStartFunction($range), self::config($config));
    }

    public static function rangeEnd(RangeValue $range, ?ExpressionTreeConfig $config = null): DateTimeValue
    {
        return self::decorateDateTime(new RangeEndFunction($range), self::config($config));
    }

    /**
     * @param array<string>|ArrayValue<mixed> $holidays "YYYY-MM-DD" dates
     * @param array<int>|ArrayValue<mixed>|null $weekend ISO days of the week; Saturday and Sunday when null
     */
    public static function isBusinessDay(
        DateTimeValue $datetime,
        array|ArrayValue $holidays = [],
        array|ArrayValue|null $weekend = null,
        ?ExpressionTreeConfig $config = null,
    ): BooleanValue {
        return self::decorateBoolean(
            new BusinessDayIsFunction($datetime, self::wrapAs(ArrayValue::class, $holidays), self::weekendOf($weekend)),
            self::config($config),
        );
    }

    /**
     * @param array<string>|ArrayValue<mixed> $holidays "YYYY-MM-DD" dates
     * @param array<int>|ArrayValue<mixed>|null $weekend ISO days of the week; Saturday and Sunday when null
     */
    public static function addBusinessDays(
        DateTimeValue $datetime,
        int|IntegerValue $days,
        array|ArrayValue $holidays = [],
        array|ArrayValue|null $weekend = null,
        ?ExpressionTreeConfig $config = null,
    ): DateTimeValue {
        return self::decorateDateTime(
            new BusinessDaysAddFunction(
                $datetime,
                self::wrapAs(IntegerValue::class, $days),
                self::wrapAs(ArrayValue::class, $holidays),
                self::weekendOf($weekend),
            ),
            self::config($config),
        );
    }

    /**
     * @param array<string>|ArrayValue<mixed> $holidays "YYYY-MM-DD" dates
     * @param array<int>|ArrayValue<mixed>|null $weekend ISO days of the week; Saturday and Sunday when null
     */
    public static function businessDaysBetween(
        DateTimeValue $from,
        DateTimeValue $to,
        array|ArrayValue $holidays = [],
        array|ArrayValue|null $weekend = null,
        ?ExpressionTreeConfig $config = null,
    ): IntegerValue {
        return self::decorateInteger(
            new BusinessDaysBetweenFunction($from, $to, self::wrapAs(ArrayValue::class, $holidays), self::weekendOf($weekend)),
            self::config($config),
        );
    }

    private static function timeOf(string|StringValue $time): StringValue
    {
        return is_string($time) ? new TimeLiteral($time) : $time;
    }

    /**
     * @param array<int>|ArrayValue<mixed>|null $weekend
     *
     * @return ArrayValue<mixed>|null
     */
    private static function weekendOf(array|ArrayValue|null $weekend): ?ArrayValue
    {
        return $weekend === null ? null : self::wrapAs(ArrayValue::class, $weekend);
    }

    private static function config(?ExpressionTreeConfig $config): ExpressionTreeConfig
    {
        return $config ?? new ExpressionTreeConfig();
    }

    private static function decorateDateTime(DateTimeValue $node, ExpressionTreeConfig $config): DateTimeValue
    {
        $decorated = self::decorate($node, $config);

        return $decorated instanceof DateTimeValue ? $decorated : new DecoratedDateTimeValue($decorated);
    }

    private static function decorateInterval(IntervalValue $node, ExpressionTreeConfig $config): IntervalValue
    {
        $decorated = self::decorate($node, $config);

        return $decorated instanceof IntervalValue ? $decorated : new DecoratedIntervalValue($decorated);
    }

    private static function decorateRange(RangeValue $node, ExpressionTreeConfig $config): RangeValue
    {
        $decorated = self::decorate($node, $config);

        return $decorated instanceof RangeValue ? $decorated : new DecoratedRangeValue($decorated);
    }
}
