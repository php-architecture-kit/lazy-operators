<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Greater Than Or Equal')]
#[Formula('f(left, right) = left >= right')]
#[Description('Greater Than Or Equal returns true when the left operand is greater than or equal to the right operand.')]
class GreaterThanOrEqualOperator implements BooleanValue
{
    public const KEY = 'greater_than_or_equal';
    public const UID = '6f0fb991-def5-4881-a6e9-0813d7b4c0f2';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() >= ($this->right)();
    }
}
