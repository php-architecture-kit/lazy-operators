<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\NormalizesPrecisionValues;

class BcAddFunction implements PrecisionNumberValue
{
    use GuardsNativeFunction;
    use NormalizesPrecisionValues;

    public const KEY = 'bcmath_add';
    public const UID = '2ebc9b5a-1744-4902-9ebb-90ae50bd7e30';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bcadd';
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
        return bcadd((string) $this->left->bcValue(), (string) $this->right->bcValue(), $this->scale);
    }

    public static function formula(): string
    {
        return 'f(left, right, scale) = left + right, computed to scale decimal digits via bcadd';
    }
}
