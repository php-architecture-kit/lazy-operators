<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression;

class ExpressionTreeConfig
{
    public function __construct(
        public readonly ?Decorator $decorator = null,
    ) {
    }
}
