<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading;

use DateInterval;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\IntervalValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Reading')]
#[Name('Difference')]
#[Formula('f(from, to) = from->diff(to)')]
#[Description('Difference returns the calendar interval from one date-time to another (negative when to is earlier). Intervals cannot be compared with < or >; compare seconds with Seconds Between instead.')]
final class DateTimeDiffFunction implements IntervalValue
{
    public const KEY = 'datetime_diff';
    public const UID = '303f9630-a648-409d-8e75-d17d72010090';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $from,
        public readonly DateTimeValue $to,
    ) {
    }

    public function __invoke(): DateInterval
    {
        return ($this->from)()->diff(($this->to)());
    }
}
