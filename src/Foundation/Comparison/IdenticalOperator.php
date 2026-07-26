<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class IdenticalOperator implements ComparisonOperator
{
    public const KEY = 'identical';
    public const UID = '65764c6c-aced-434d-8fa6-4762eb9612e8';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() === ($this->right)();
    }
}
