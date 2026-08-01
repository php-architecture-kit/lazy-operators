<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\NormalizesPrecisionValues;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

#[Group('BcMath')]
#[Name('BC Add')]
#[Formula('f(left, right, scale) = left + right, computed to scale decimal digits via bcadd')]
#[Description('BC Add returns the sum of two arbitrary-precision numbers, computed to the given scale via bcadd.')]
class BcAddFunction implements PrecisionNumberValue
{
    use GuardsNativeFunction;
    use NormalizesPrecisionValues;

    public const KEY = 'bcmath_add';
    public const UID = '2ebc9b5a-1744-4902-9ebb-90ae50bd7e30';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bcadd';
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

    public function __invoke(): int|float
    {
        return $this->compute() + 0;
    }

    public function bcValue(): Number
    {
        return new Number($this->compute());
    }

    /**
     * @return numeric-string
     */
    private function compute(): string
    {
        return bcadd((string) $this->left->bcValue(), (string) $this->right->bcValue(), $this->scale?->__invoke());
    }
}
