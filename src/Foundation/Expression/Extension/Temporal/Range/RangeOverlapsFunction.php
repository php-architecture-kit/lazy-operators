<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

#[Group('Temporal | Range')]
#[Name('Overlaps')]
#[Formula('f(left, right) = left.start < right.end and right.start < left.end')]
#[Description('Overlaps checks whether two ranges share any instant; ranges that only touch (one ends where the other starts) do not overlap.')]
final class RangeOverlapsFunction implements BooleanValue
{
    public const KEY = 'range_overlaps';
    public const UID = 'daefe7be-73c8-40ba-a2d6-3940a1196583';
    public const VERSION = '1.0';

    public function __construct(
        public readonly RangeValue $left,
        public readonly RangeValue $right,
    ) {
    }

    public function __invoke(): bool
    {
        return ($this->left)()->overlaps(($this->right)());
    }
}
