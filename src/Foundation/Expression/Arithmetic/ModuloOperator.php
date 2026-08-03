<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Arithmetic')]
#[Name('Modulo')]
#[Formula('f(left, right) = left mod right')]
#[Description('Modulo returns the remainder of dividing the left operand by the right operand.')]
class ModuloOperator implements NumberValue
{
    public const KEY = 'modulo';
    public const UID = '64306250-dc4f-4dd4-830c-70649e9dbbd8';
    public const VERSION = '1.0';

    public function __construct(
        public readonly NumberValue $left,
        public readonly NumberValue $right,
    ) {}

    public function __invoke(): float|int
    {
        return ($this->left)() % ($this->right)();
    }
}
