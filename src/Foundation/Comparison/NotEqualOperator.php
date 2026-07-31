<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Not Equal')]
#[Formula('f(left, right) = left <> right')]
#[Description('Not Equal returns true when the left and right operands are not equal under PHP\'s loose (!=) comparison.')]
class NotEqualOperator implements BooleanValue
{
    public const KEY = 'not_equal';
    public const UID = '1fe87371-fca5-4303-ae40-7bfaa4eb5f78';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() != ($this->right)();
    }
}
