<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Not Identical')]
#[Formula('f(left, right) = left is not identical to right')]
#[Description('Not Identical returns true when the left and right operands differ in value or in type, under PHP\'s strict (!==) comparison.')]
class NotIdenticalOperator implements BooleanValue
{
    public const KEY = 'not_identical';
    public const UID = '90d06e8e-fbdf-4ba7-afad-9be706714d81';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() !== ($this->right)();
    }
}
