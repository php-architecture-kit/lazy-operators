<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;

/**
 * Re-exposes an already-decorated node (whose decorator only implements the generic
 * `Decorator extends Expression` contract) as a `StringValue`, so a decorated string-typed
 * pipeline can keep chaining into the next operator's narrowed constructor.
 */
final class DecoratedStringValue implements StringValue
{
    public function __construct(
        private readonly Expression $decorated,
    ) {
    }

    public function __invoke(): string
    {
        $value = ($this->decorated)();
        assert(is_string($value));

        return $value;
    }
}
