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
