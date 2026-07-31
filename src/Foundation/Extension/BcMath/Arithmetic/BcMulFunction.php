<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\NormalizesPrecisionValues;

class BcMulFunction implements PrecisionNumberValue
{
    use GuardsNativeFunction;
    use NormalizesPrecisionValues;

    public const KEY = 'bcmath_mul';
    public const UID = '05d100f9-0142-4a29-8d67-bb4a73014a23';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bcmul';
    public readonly Expression&PrecisionNumberValue $left;
    public readonly Expression&PrecisionNumberValue $right;

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
        return bcmul((string) $this->left->bcValue(), (string) $this->right->bcValue(), $this->scale);
    }

    public static function formula(): string
    {
        return 'f(left, right, scale) = left * right, computed to scale decimal digits via bcmul';
    }
}
