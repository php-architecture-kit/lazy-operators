<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DateTimeRange;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;

/**
 * Re-exposes an already-decorated node as a range, so a decorated temporal pipeline can keep
 * chaining into the next node's narrowed constructor (see DecoratesNodes).
 */
final class DecoratedRangeValue implements RangeValue
{
    public function __construct(
        private readonly Expression $decorated,
    ) {
    }

    public function __invoke(): DateTimeRange
    {
        $value = ($this->decorated)();
        assert($value instanceof DateTimeRange);

        return $value;
    }
}
