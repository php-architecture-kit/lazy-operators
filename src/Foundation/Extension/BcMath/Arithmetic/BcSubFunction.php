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
#[Name('BC Sub')]
#[Formula('f(left, right, scale) = left - right, computed to scale decimal digits via bcsub')]
#[Description('BC Sub returns the difference of two arbitrary-precision numbers, computed to the given scale via bcsub.')]
class BcSubFunction implements NumberValue
{
    use GetScaleFunction;
    use GuardsNativeFunction;

    public const KEY = 'bcmath_sub';
    public const UID = 'e6e40e67-e321-4381-bd50-374900e97aee';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bcsub';

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

        return bcsub($left, $right, $scale);
    }
}
