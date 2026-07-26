<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class LessThanOperator implements ComparisonOperator
{
    public function __construct(
        private readonly Expression $left,
        private readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() < ($this->right)();
    }
}
