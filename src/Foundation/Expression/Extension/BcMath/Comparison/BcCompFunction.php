<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('BcMath')]
#[Name('BC Comp')]
#[Formula('f(left, right, scale) = -1|0|1 comparing left and right to scale decimal digits via bccomp')]
#[Description('BC Comp compares two arbitrary-precision numbers to the given scale via bccomp, returning -1, 0, or 1.')]
class BcCompFunction implements IntegerValue
{
    use GuardsNativeFunction;

    public const KEY = 'bcmath_comp';
    public const UID = '923053e1-492b-4d87-b36c-e414e4962dc9';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bccomp';

    public function __construct(
        public readonly NumberValue $left,
        public readonly NumberValue $right,
        public readonly ?IntegerValue $scale = null,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int
    {
        return bccomp((string) $this->left->__invoke(), (string) $this->right->__invoke(), $this->scale?->__invoke());
    }
}
