<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Arithmetic')]
#[Name('Division')]
#[Formula('f(left, right) = left : right')]
#[Description('Division returns the left operand divided by the right operand.')]
class DivisionOperator implements NumberValue
{
    public const KEY = 'division';
    public const UID = 'd2040699-3b11-4d05-aedd-9acb0950e790';
    public const VERSION = '1.0';

    public function __construct(
        public readonly NumberValue $left,
        public readonly NumberValue $right,
    ) {}

    public function __invoke(): float|int
    {
        return ($this->left)() / ($this->right)();
    }
}
