<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class GreaterThanOperator implements ComparisonOperator
{
    public const KEY = 'greater_than';
    public const UID = 'aa2f5486-3fe6-4d75-a98e-12eba565812a';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() > ($this->right)();
    }
}
