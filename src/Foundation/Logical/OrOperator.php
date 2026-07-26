<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class OrOperator implements LogicalOperator
{
    public const KEY = 'or';
    public const UID = 'cc18dd9b-6516-4395-9263-cfe8e97d2e91';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() || ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = left OR right';
    }
}
