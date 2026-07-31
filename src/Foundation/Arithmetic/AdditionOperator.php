<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

class AdditionOperator implements NumberValue
{
    public const KEY = 'addition';
    public const UID = 'b461bc1d-aa8f-4d2c-95c4-b82394b01ca5';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression&NumberValue $left,
        public readonly Expression&NumberValue $right,
    ) {
    }
    
    public function __invoke(): float|int
    {
        return ($this->left)() + ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = left + right';
    }
}
