<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Comparison')]
#[Name('Equal')]
#[Formula('f(left, right) = left = right')]
#[Description('Equal returns true when the left and right operands are equal under PHP\'s loose (==) comparison.')]
class EqualOperator implements BooleanValue
{
    public const KEY = 'equal';
    public const UID = '63928bd8-64d9-4a19-91f7-15c0927477b3';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() == ($this->right)();
    }
}
