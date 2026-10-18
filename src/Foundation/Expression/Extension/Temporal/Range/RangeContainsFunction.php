<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

#[Group('Temporal | Range')]
#[Name('Contains')]
#[Formula('f(range, datetime) = start <= datetime < end')]
#[Description('Contains checks whether a date-time falls inside a range; the range\'s end itself is outside.')]
final class RangeContainsFunction implements BooleanValue
{
    public const KEY = 'range_contains';
    public const UID = '1a07092e-7d89-41e8-b322-c00b9fb73c59';
    public const VERSION = '1.0';

    public function __construct(
        public readonly RangeValue $range,
        public readonly DateTimeValue $datetime,
    ) {
    }

    public function __invoke(): bool
    {
        return ($this->range)()->contains(($this->datetime)());
    }
}
