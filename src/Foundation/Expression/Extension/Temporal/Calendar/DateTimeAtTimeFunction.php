<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DstFold;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DstGap;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DateTimes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\WallClock;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

#[Group('Temporal | Calendar')]
#[Name('At Time')]
#[Formula('f(datetime, time, gap, fold) = the same day as datetime, at the wall-clock time')]
#[Description('At Time moves a date-time to the given wall-clock time ("17:00") on the same day in its own timezone. A time the clocks skip is moved forward (gap Forward) or rejected (gap Reject); a time the clocks show twice resolves to its second (fold Later) or first (fold Earlier) occurrence.')]
final class DateTimeAtTimeFunction implements DateTimeValue
{
    public const KEY = 'datetime_at_time';
    public const UID = '7986a328-db89-4be5-acda-a3abe34b1106';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $datetime,
        public readonly StringValue $time,
        public readonly DstGap $gap = DstGap::Forward,
        public readonly DstFold $fold = DstFold::Later,
    ) {
    }

    public function __invoke(): DateTimeImmutable
    {
        [$hour, $minute, $second] = DateTimes::time(($this->time)());

        return WallClock::at(($this->datetime)(), $hour, $minute, $second, $this->gap, $this->fold);
    }
}
