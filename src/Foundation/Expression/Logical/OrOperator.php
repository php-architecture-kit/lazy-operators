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
#[Name('Or')]
#[Formula('f(left, right) = left OR right')]
#[Description('OR (inclusive OR) works by comparing two inputs and returning true (1) if at least one of the inputs is true, meaning either one or both are true. It outputs false (0) only if both inputs are false.')]
class OrOperator implements BooleanValue
{
    public const KEY = 'or';
    public const UID = 'cc18dd9b-6516-4395-9263-cfe8e97d2e91';
    public const VERSION = '1.0';

    public function __construct(
        public readonly BooleanValue $left,
        public readonly BooleanValue $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() || ($this->right)();
    }
}
