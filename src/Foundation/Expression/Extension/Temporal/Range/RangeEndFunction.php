<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\OpenRangeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Range')]
#[Name('Range End')]
#[Formula('f(range) = range.end')]
#[Description('Range End returns the instant a range ends at, which is itself outside the range. A range without an end fails.')]
final class RangeEndFunction implements DateTimeValue
{
    public const KEY = 'range_end';
    public const UID = '76d07b31-aecd-4491-bab5-4e32fef7520a';
    public const VERSION = '1.0';

    public function __construct(
        public readonly RangeValue $range,
    ) {
    }

    public function __invoke(): DateTimeImmutable
    {
        $range = ($this->range)();

        return $range->end ?? throw OpenRangeException::noEnd((string) $range);
    }
}
