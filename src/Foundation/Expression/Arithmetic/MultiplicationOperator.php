<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('Arithmetic')]
#[Name('Multiplication')]
#[Formula('f(left, right) = left x right')]
#[Description('Multiplication returns the product of the left and right operands.')]
class MultiplicationOperator implements NumberValue
{
    public const KEY = 'multiplication';
    public const UID = '0ed96a9f-75dc-45bc-a9f5-5dbdb48cbd5a';
    public const VERSION = '1.0';

    public function __construct(
        public readonly NumberValue $left,
        public readonly NumberValue $right,
    ) {}

    public function __invoke(): float|int
    {
        return ($this->left)() * ($this->right)();
    }
}
