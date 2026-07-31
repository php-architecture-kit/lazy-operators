<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Greater Than')]
#[Formula('f(left, right) = left > right')]
#[Description('Greater Than returns true when the left operand is greater than the right operand.')]
class GreaterThanOperator implements BooleanValue
{
    public const KEY = 'greater_than';
    public const UID = 'aa2f5486-3fe6-4d75-a98e-12eba565812a';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() > ($this->right)();
    }
}
