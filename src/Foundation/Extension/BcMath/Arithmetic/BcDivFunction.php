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
#[Name('BC Div')]
#[Formula('f(dividend, divisor, scale) = dividend / divisor, computed to scale decimal digits via bcdiv; '
            . 'throws DivisionByZeroError natively when divisor is zero')]
#[Description('BC Div returns the quotient of two arbitrary-precision numbers, computed to the given scale via bcdiv.')]
class BcDivFunction implements NumberValue
{
    use GetScaleFunction;
    use GuardsNativeFunction;

    public const KEY = 'bcmath_div';
    public const UID = '0e966b45-8f22-48de-a9c9-c81d1df7f299';
    public const VERSION = '1.0';
    public const DEFAULT_SCALE = 16;
    public const DEFAULT_MIN_SCALE = 8;

    private const NATIVE_FUNCTION = 'bcdiv';

    public function __construct(
        public readonly NumberValue $dividend,
        public readonly NumberValue $divisor,
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
        $dividend = (string) $this->dividend->__invoke();
        $divisor = (string) $this->divisor->__invoke();
        $scale = $this->scale?->__invoke();

        if ($scale === null) {
            $scale = $this->getScale($dividend) + $this->getScale($divisor);

            if ($scale < self::DEFAULT_MIN_SCALE) {
                $scale = self::DEFAULT_SCALE;
            }
        }

        return bcdiv($dividend, $divisor, $scale);
    }
}
