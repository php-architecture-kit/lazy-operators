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
#[Name('Less Than Or Equal')]
#[Formula('f(left, right) = left <= right')]
#[Description('Less Than Or Equal returns true when the left operand is smaller than or equal to the right operand.')]
class LessThanOrEqualOperator implements BooleanValue
{
    public const KEY = 'less_than_or_equal';
    public const UID = '858cf616-69a8-4410-b5ed-816e1d2ae5a4';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() <= ($this->right)();
    }
}
