<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class AdditionOperator implements ArithmeticOperator
{
    public const KEY = 'addition';
    public const UID = 'b461bc1d-aa8f-4d2c-95c4-b82394b01ca5';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): float|int
    {
        return ($this->left)() + ($this->right)();
    }
}
