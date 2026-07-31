<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Less Than')]
#[Formula('f(left, right) = left < right')]
#[Description('Less Than returns true when the left operand is smaller than the right operand.')]
class LessThanOperator implements BooleanValue
{
    public const KEY = 'less_than';
    public const UID = '4898fe1a-0e23-46f9-b45a-29644b84cb36';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() < ($this->right)();
    }
}
