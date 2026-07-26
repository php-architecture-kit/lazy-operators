<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class NotEqualOperator implements ComparisonOperator
{
    public const KEY = 'not_equal';
    public const UID = '1fe87371-fca5-4303-ae40-7bfaa4eb5f78';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() != ($this->right)();
    }
}
