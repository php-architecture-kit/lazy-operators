<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class NotOperator implements LogicalOperator
{
    public const KEY = 'not';
    public const UID = 'a434e8ae-e373-4a9b-82f0-1d54a4626e20';
    public const VERSION = '1.0';

    public function __construct(
        private readonly Expression $expression,
    ) {
    }
    
    public function __invoke(): bool
    {
        return !($this->expression)();
    }
}
