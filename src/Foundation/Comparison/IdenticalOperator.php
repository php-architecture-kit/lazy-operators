<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Identical')]
#[Formula('f(left, right) = left is identical to right')]
#[Description('Identical returns true when the left and right operands are equal in both value and type, under PHP\'s strict (===) comparison.')]
class IdenticalOperator implements BooleanValue
{
    public const KEY = 'identical';
    public const UID = '65764c6c-aced-434d-8fa6-4762eb9612e8';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() === ($this->right)();
    }
}
