<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;

#[Group('Logical')]
#[Name('And')]
#[Formula('f(left, right) = left AND right')]
#[Description('AND (logical AND) works by comparing two inputs and returning true (1) only when both inputs are true. It outputs false (0) if any of the inputs are false, meaning one or both are false.')]
class AndOperator implements BooleanValue
{
    public const KEY = 'and';
    public const UID = '72c13fa3-d055-4de5-bc79-1207b9c8757b';
    public const VERSION = '1.0';

    public function __construct(
        public readonly BooleanValue $left,
        public readonly BooleanValue $right,
    ) {}

    public function __invoke(): bool
    {
        return ($this->left)() && ($this->right)();
    }
}
