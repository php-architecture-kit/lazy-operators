<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Subtraction')]
#[Formula('f(left, right) = left - right')]
#[Description('Subtraction returns the left operand minus the right operand.')]
class SubtractionOperator implements NumberValue
{
    public const KEY = 'subtraction';
    public const UID = '22df52b8-87bf-4483-a97a-5c56139447b0';
    public const VERSION = '1.0';

    public function __construct(
        public readonly NumberValue $left,
        public readonly NumberValue $right,
    ) {
    }
    
    public function __invoke(): float|int
    {
        return ($this->left)() - ($this->right)();
    }
}
