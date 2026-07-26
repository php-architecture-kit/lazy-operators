<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class NotOperator implements LogicalOperator
{
    public function __construct(
        private readonly Expression $expression,
    ) {
    }
    
    public function __invoke(): bool
    {
        return !($this->expression)();
    }
}
