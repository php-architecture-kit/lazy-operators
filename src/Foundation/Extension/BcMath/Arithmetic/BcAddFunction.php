<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\GetScaleFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\GuardsNativeFunction;
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
class BcAddFunction implements NumberValue
{
    use GetScaleFunction;
    use GuardsNativeFunction;

    public const KEY = 'bcmath_add';
    public const UID = '2ebc9b5a-1744-4902-9ebb-90ae50bd7e30';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bcadd';

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
            $scale = max($this->getScale($left), $this->getScale($right));
        }

        return bcadd($left, $right, $scale);
    }
}
