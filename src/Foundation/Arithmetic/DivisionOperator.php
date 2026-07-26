<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class DivisionOperator implements ArithmeticOperator
{
    public const KEY = 'division';
    public const UID = 'd2040699-3b11-4d05-aedd-9acb0950e790';
    public const VERSION = '1.0';

    public function __construct(
        private readonly Expression $left,
        private readonly Expression $right,
    ) {}

    public function __invoke(): float|int
    {
        return ($this->left)() / ($this->right)();
    }
}
