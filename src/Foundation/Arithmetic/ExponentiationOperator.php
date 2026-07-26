<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class ExponentiationOperator implements ArithmeticOperator
{
    public const KEY = 'exponentiation';
    public const UID = '2c1511f4-b610-4837-bf43-4bb0263a6670';
    public const VERSION = '1.0';

    public function __construct(
        private readonly Expression $left,
        private readonly Expression $right,
    ) {}

    public function __invoke(): float|int
    {
        return ($this->left)() ** ($this->right)();
    }
}
