<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Arithmetic')]
#[Name('Addition')]
#[Formula('f(left, right) = left + right')]
#[Description('Addition returns the sum of the left and right operands.')]
class AdditionOperator implements NumberValue
{
    public const KEY = 'addition';
    public const UID = 'b461bc1d-aa8f-4d2c-95c4-b82394b01ca5';
    public const VERSION = '1.0';

    public function __construct(
        public readonly NumberValue $left,
        public readonly NumberValue $right,
    ) {
    }
    
    public function __invoke(): float|int
    {
        return ($this->left)() + ($this->right)();
    }
}
