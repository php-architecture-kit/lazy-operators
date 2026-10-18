<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Cast;

use DateInterval;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DateTimes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\IntervalValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Cast')]
#[Name('Interval Cast')]
#[Formula('f(value) = value as an interval')]
#[Description('Interval Cast reads a DateInterval or an ISO 8601 interval string such as "P7D" or "PT30M".')]
final class IntervalCast implements IntervalValue
{
    public const KEY = 'cast_interval';
    public const UID = '53523295-a7fe-4c66-a647-479d6c9d942a';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $value,
    ) {
    }

    public function __invoke(): DateInterval
    {
        return DateTimes::interval(($this->value)());
    }
}
