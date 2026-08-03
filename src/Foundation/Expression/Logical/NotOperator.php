<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Logical')]
#[Name('Not')]
#[Formula('f(expression) = NOT expression')]
#[Description('NOT (logical NOT or inversion) works by taking a single input and returning the exact opposite value. It outputs true (1) if the input is false, and outputs false (0) if the input is true.')]
class NotOperator implements BooleanValue
{
    public const KEY = 'not';
    public const UID = 'a434e8ae-e373-4a9b-82f0-1d54a4626e20';
    public const VERSION = '1.0';

    public function __construct(
        public readonly BooleanValue $expression,
    ) {
    }
    
    public function __invoke(): bool
    {
        return !($this->expression)();
    }
}
