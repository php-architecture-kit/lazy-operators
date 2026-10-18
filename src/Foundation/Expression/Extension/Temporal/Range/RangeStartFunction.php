<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Range')]
#[Name('Range Start')]
#[Formula('f(range) = range.start')]
#[Description('Range Start returns the first instant of a range.')]
final class RangeStartFunction implements DateTimeValue
{
    public const KEY = 'range_start';
    public const UID = '617f762d-1bad-4d81-af6b-2cf83240ee34';
    public const VERSION = '1.0';

    public function __construct(
        public readonly RangeValue $range,
    ) {
    }

    public function __invoke(): DateTimeImmutable
    {
        return ($this->range)()->start;
    }
}
