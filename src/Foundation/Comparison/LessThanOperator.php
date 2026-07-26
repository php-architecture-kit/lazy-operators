<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class LessThanOperator implements ComparisonOperator
{
    public const KEY = 'less_than';
    public const UID = '4898fe1a-0e23-46f9-b45a-29644b84cb36';
    public const VERSION = '1.0';

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
