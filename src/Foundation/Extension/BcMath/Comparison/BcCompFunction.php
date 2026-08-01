<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Comparison;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\NormalizesPrecisionValues;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('BcMath')]
#[Name('BC Comp')]
#[Formula('f(left, right, scale) = -1|0|1 comparing left and right to scale decimal digits via bccomp')]
#[Description('BC Comp compares two arbitrary-precision numbers to the given scale via bccomp, returning -1, 0, or 1.')]
class BcCompFunction implements IntegerValue
{
    use GuardsNativeFunction;
    use NormalizesPrecisionValues;

    public const KEY = 'bcmath_comp';
    public const UID = '923053e1-492b-4d87-b36c-e414e4962dc9';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bccomp';
    public readonly PrecisionNumberValue $left;
    public readonly PrecisionNumberValue $right;

    public function __construct(
        NumberValue $left,
        NumberValue $right,
        public readonly ?IntegerValue $scale = null,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);

        $this->left = self::normalize($left);
        $this->right = self::normalize($right);
    }

    public function __invoke(): int
    {
        return bccomp((string) $this->left->bcValue(), (string) $this->right->bcValue(), $this->scale?->__invoke());
    }
}
