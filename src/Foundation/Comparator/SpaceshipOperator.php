<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Spaceship')]
#[Formula('f(left, right) = sgn(left - right)')]
#[Description('Spaceship compares the left and right operands and returns -1, 0, or 1 depending on whether the left operand is smaller than, equal to, or greater than the right operand.')]
class SpaceshipOperator implements IntegerValue
{
    public const KEY = 'spaceship';
    public const UID = '611d67d6-5612-449a-89bc-8ece8bc39a86';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {}

    public function __invoke(): int
    {
        return ($this->left)() <=> ($this->right)();
    }
}
