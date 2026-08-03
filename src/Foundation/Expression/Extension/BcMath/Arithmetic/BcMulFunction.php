<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Support\GetScaleFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;

#[Group('BcMath')]
#[Name('BC Mul')]
#[Formula('f(left, right, scale) = left * right, computed to scale decimal digits via bcmul')]
#[Description('BC Mul returns the product of two arbitrary-precision numbers, computed to the given scale via bcmul.')]
class BcMulFunction implements NumberValue
{
    use GetScaleFunction;
    use GuardsNativeFunction;

    public const KEY = 'bcmath_mul';
    public const UID = '05d100f9-0142-4a29-8d67-bb4a73014a23';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bcmul';

    public function __construct(
        public readonly NumberValue $left,
        public readonly NumberValue $right,
        public readonly ?IntegerValue $scale = null,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        return $this->compute() + 0;
    }

    /**
     * @return numeric-string
     */
    private function compute(): string
    {
        $left = (string) $this->left->__invoke();
        $right = (string) $this->right->__invoke();
        $scale = $this->scale?->__invoke();

        if ($scale === null) {
            $scale = $this->getScale($left) + $this->getScale($right);
        }

        return bcmul($left, $right, $scale);
    }
}
