<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DatePart;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DateTimes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

#[Group('Temporal | Reading')]
#[Name('Time Of Day Between')]
#[Formula('f(datetime, from, to) = from <= wall-clock time of datetime < to')]
#[Description('Time Of Day Between checks the date-time\'s wall-clock time in its own timezone against a half-open window [from, to), e.g. "09:00" to "17:00". A window whose end is before its start wraps past midnight ("22:00" to "06:00"); equal bounds are an empty window.')]
final class TimeOfDayBetweenFunction implements BooleanValue
{
    public const KEY = 'time_of_day_between';
    public const UID = 'e912846b-059f-44e1-a202-13b51fa8c235';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $datetime,
        public readonly StringValue $from,
        public readonly StringValue $to,
    ) {
    }

    public function __invoke(): bool
    {
        $time = DatePart::SecondOfDay->read(($this->datetime)());
        $from = DateTimes::secondOfDay(($this->from)());
        $to = DateTimes::secondOfDay(($this->to)());

        return $from <= $to
            ? $from <= $time && $time < $to
            : $time >= $from || $time < $to;
    }
}
