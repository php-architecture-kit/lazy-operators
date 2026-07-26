<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class SubtractionOperator implements ArithmeticOperator
{
    public const KEY = 'subtraction';
    public const UID = '22df52b8-87bf-4483-a97a-5c56139447b0';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): float|int
    {
        return ($this->left)() - ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = left - right';
    }
}
