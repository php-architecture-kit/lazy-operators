<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class ModuloOperator implements ArithmeticOperator
{
    public const KEY = 'modulo';
    public const UID = '64306250-dc4f-4dd4-830c-70649e9dbbd8';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {}

    public function __invoke(): float|int
    {
        return ($this->left)() % ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = left mod right';
    }
}
