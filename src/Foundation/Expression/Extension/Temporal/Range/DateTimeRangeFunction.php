<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DateTimeRange;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Range')]
#[Name('Range')]
#[Formula('f(start, end) = [start, end)')]
#[Description('Range is a half-open period of validity, [start, end): it contains its start but not its end, so back-to-back ranges never overlap. Leave end out for a range that lasts indefinitely. An end that is not after the start fails when the range is evaluated.')]
final class DateTimeRangeFunction implements RangeValue
{
    public const KEY = 'datetime_range';
    public const UID = '6cd48053-0bd8-4b8c-8c6b-9f83e4bb9579';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $start,
        public readonly ?DateTimeValue $end = null,
    ) {
    }

    public function __invoke(): DateTimeRange
    {
        return new DateTimeRange(($this->start)(), $this->end === null ? null : ($this->end)());
    }
}
