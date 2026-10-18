<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\MonthOverflow;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\Intervals;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\IntervalValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Calendar')]
#[Name('Add Interval')]
#[Formula('f(datetime, interval, overflow) = datetime + interval')]
#[Description('Add Interval adds an interval in the date-time\'s own timezone. Calendar parts follow the calendar ("P1D" across a clock change is still the same wall-clock time the next day), time parts follow elapsed time ("PT24H" is exactly 24 hours). With overflow Clip (the default) a month step stays in the target month: 31 January + "P1M" is 28 February.')]
final class DateTimeAddFunction implements DateTimeValue
{
    public const KEY = 'datetime_add';
    public const UID = '1c9a1100-7f5b-4ba5-a622-eb67201d1cb4';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $datetime,
        public readonly IntervalValue $interval,
        public readonly MonthOverflow $overflow = MonthOverflow::Clip,
    ) {
    }

    public function __invoke(): DateTimeImmutable
    {
        return Intervals::apply(($this->datetime)(), ($this->interval)(), false, $this->overflow);
    }
}
