<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class AndOperator implements LogicalOperator
{
    public const KEY = 'and';
    public const UID = '72c13fa3-d055-4de5-bc79-1207b9c8757b';
    public const VERSION = '1.0';

    public function __construct(
        private readonly Expression $left,
        private readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() && ($this->right)();
    }
}
