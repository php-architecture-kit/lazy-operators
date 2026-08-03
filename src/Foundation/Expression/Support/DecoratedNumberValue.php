<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

/**
 * Re-exposes an already-decorated node (whose decorator only implements the generic
 * `Decorator extends Expression` contract) as a `NumberValue`, so a decorated numeric
 * pipeline can keep chaining into the next operator's narrowed constructor.
 */
final class DecoratedNumberValue implements NumberValue
{
    public function __construct(
        private readonly Expression $decorated,
    ) {
    }

    public function __invoke(): float|int
    {
        $value = ($this->decorated)();
        assert(is_int($value) || is_float($value));

        return $value;
    }
}
