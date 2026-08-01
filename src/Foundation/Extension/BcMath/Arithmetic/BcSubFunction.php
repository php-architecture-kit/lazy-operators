<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\NormalizesPrecisionValues;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('BC Sub')]
#[Formula('f(left, right, scale) = left - right, computed to scale decimal digits via bcsub')]
#[Description('BC Sub returns the difference of two arbitrary-precision numbers, computed to the given scale via bcsub.')]
class BcSubFunction implements PrecisionNumberValue
{
    use GuardsNativeFunction;
    use NormalizesPrecisionValues;

    public const KEY = 'bcmath_sub';
    public const UID = 'e6e40e67-e321-4381-bd50-374900e97aee';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bcsub';
    public readonly PrecisionNumberValue $left;
    public readonly PrecisionNumberValue $right;

    public function __construct(
        Number|Expression $left,
        Number|Expression $right,
        public readonly ?int $scale = null,
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
        return bcsub((string) $this->left->bcValue(), (string) $this->right->bcValue(), $this->scale);
    }
}
