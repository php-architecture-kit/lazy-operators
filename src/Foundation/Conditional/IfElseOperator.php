<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class IfElseOperator implements Expression
{
    public const KEY = 'if_else';
    public const UID = '6d308318-d7e4-4f8c-9ce1-8db1266b1579';
    public const VERSION = '1.0';

    public function __construct(
        private readonly Expression $condition,
        private readonly Expression $then,
        private readonly Expression $else,
    ) {}

    public function __invoke(): mixed
    {
        return ($this->condition)() ? ($this->then)() : ($this->else)();
    }
}
