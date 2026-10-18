<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal;

use DateInterval;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DateTimes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\IntervalValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Literal')]
#[Name('Interval Literal')]
#[Formula('f(value) = value')]
#[Description('Interval Literal is a fixed ISO 8601 interval such as "P7D" (7 calendar days), "P1M" (1 month) or "PT30M" (30 minutes), checked when the node is built.')]
final class IntervalLiteral implements IntervalValue
{
    public const KEY = 'interval_literal';
    public const UID = '231640a7-d93d-4b8e-9e41-f70b1fec41ac';
    public const VERSION = '1.0';
    private readonly DateInterval $interval;

    public function __construct(
        public readonly string $value,
    ) {
        $this->interval = DateTimes::interval($value);
    }

    public function __invoke(): DateInterval
    {
        return clone $this->interval;
    }
}
