<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

/**
 * Re-exposes an already-decorated node (whose decorator only implements the generic
 * `Decorator extends Expression` contract) as a `BooleanValue`, so a decorated logical pipeline
 * can keep chaining into the next logical operator's narrowed constructor.
 */
final class DecoratedBooleanValue implements BooleanValue
{
    public function __construct(
        private readonly Expression $decorated,
    ) {
    }

    public function __invoke(): bool
    {
        $value = ($this->decorated)();
        assert(is_bool($value));

        return $value;
    }
}
