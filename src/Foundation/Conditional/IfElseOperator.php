<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('If Else')]
#[Formula('f(condition, then, else) = IF condition THEN then ELSE else')]
#[Description('If Else evaluates a boolean condition and returns the then branch when it is true, or the else branch when it is false.')]
class IfElseOperator implements Expression
{
    public const KEY = 'if_else';
    public const UID = '6d308318-d7e4-4f8c-9ce1-8db1266b1579';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression&BooleanValue $condition,
        public readonly Expression $then,
        public readonly Expression $else,
    ) {}

    public function __invoke(): mixed
    {
        return ($this->condition)() ? ($this->then)() : ($this->else)();
    }
}
