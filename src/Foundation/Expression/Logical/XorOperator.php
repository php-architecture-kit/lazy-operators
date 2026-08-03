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
#[Name('Xor')]
#[Formula('f(left, right) = left XOR right')]
#[Description('XOR (exclusive OR) works by comparing two inputs and returning true (1) only when the inputs are different, meaning one is true and the other is false. It outputs false (0) if both inputs are the same (both true or both false).')]
class XorOperator implements BooleanValue
{
    public const KEY = 'xor';
    public const UID = 'aef9302c-c283-4c1c-ab7a-0b355e9ed445';
    public const VERSION = '1.0';

    public function __construct(
        public readonly BooleanValue $left,
        public readonly BooleanValue $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() xor ($this->right)();
    }
}
