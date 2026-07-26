<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class OrOperator implements LogicalOperator
{
    public function __construct(
        private readonly Expression $left,
        private readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() || ($this->right)();
    }
}
