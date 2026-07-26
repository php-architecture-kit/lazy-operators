<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class EqualOperator implements ComparisonOperator
{
    public const KEY = 'equal';
    public const UID = '63928bd8-64d9-4a19-91f7-15c0927477b3';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() == ($this->right)();
    }
}
