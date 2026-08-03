<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;

/**
 * Re-exposes an already-decorated node (whose decorator only implements the generic
 * `Decorator extends Expression` contract) as an `IntegerValue`, so a decorated integer
 * pipeline can keep chaining into the next operator's narrowed constructor.
 */
final class DecoratedIntegerValue implements IntegerValue
{
    public function __construct(
        private readonly Expression $decorated,
    ) {
    }

    public function __invoke(): int
    {
        $value = ($this->decorated)();
        assert(is_int($value));

        return $value;
    }
}
