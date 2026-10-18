<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support;

use DateInterval;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\IntervalValue;

/**
 * Re-exposes an already-decorated node as an interval, so a decorated temporal pipeline can keep
 * chaining into the next node's narrowed constructor (see DecoratesNodes).
 */
final class DecoratedIntervalValue implements IntervalValue
{
    public function __construct(
        private readonly Expression $decorated,
    ) {
    }

    public function __invoke(): DateInterval
    {
        $value = ($this->decorated)();
        assert($value instanceof DateInterval);

        return $value;
    }
}
