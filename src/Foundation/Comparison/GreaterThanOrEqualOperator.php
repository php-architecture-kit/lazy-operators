<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class GreaterThanOrEqualOperator implements ComparisonOperator
{
    public const KEY = 'greater_than_or_equal';
    public const UID = '6f0fb991-def5-4881-a6e9-0813d7b4c0f2';
    public const VERSION = '1.0';

    public function __construct(
        private readonly Expression $left,
        private readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() >= ($this->right)();
    }
}
