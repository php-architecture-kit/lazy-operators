<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DateTimeRange;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Range')]
#[Name('Intersection')]
#[Formula('f(left, right) = [max(starts), min(ends))')]
#[Description('Intersection returns the part two ranges share. Ranges that do not overlap have no intersection and fail; check them with Overlaps first when that can happen.')]
final class RangeIntersectionFunction implements RangeValue
{
    public const KEY = 'range_intersection';
    public const UID = 'fb7bcc36-d8ec-4365-9566-de4e7b28c16a';
    public const VERSION = '1.0';

    public function __construct(
        public readonly RangeValue $left,
        public readonly RangeValue $right,
    ) {
    }

    public function __invoke(): DateTimeRange
    {
        return ($this->left)()->intersection(($this->right)());
    }
}
