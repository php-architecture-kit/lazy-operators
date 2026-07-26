<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class SubstractionOperator implements ArithmeticOperator
{
    public function __construct(
        private readonly Expression $left,
        private readonly Expression $right,
    ) {
    }
    
    public function __invoke(): float|int
    {
        return ($this->left)() - ($this->right)();
    }
}
