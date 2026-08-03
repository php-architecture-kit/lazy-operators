<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Arithmetic')]
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
