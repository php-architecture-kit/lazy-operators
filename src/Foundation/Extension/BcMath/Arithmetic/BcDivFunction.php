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
#[Name('BC Div')]
#[Formula('f(dividend, divisor, scale) = dividend / divisor, computed to scale decimal digits via bcdiv; '
            . 'throws DivisionByZeroError natively when divisor is zero')]
#[Description('BC Div returns the quotient of two arbitrary-precision numbers, computed to the given scale via bcdiv.')]
class BcDivFunction implements PrecisionNumberValue
{
    use GuardsNativeFunction;
    use NormalizesPrecisionValues;

    public const KEY = 'bcmath_div';
    public const UID = '0e966b45-8f22-48de-a9c9-c81d1df7f299';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bcdiv';
    public readonly PrecisionNumberValue $dividend;
    public readonly PrecisionNumberValue $divisor;

    public function __construct(
        NumberValue $dividend,
        NumberValue $divisor,
        public readonly ?IntegerValue $scale = null,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);

        $this->dividend = self::normalize($dividend);
        $this->divisor = self::normalize($divisor);
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
        return bcdiv((string) $this->dividend->bcValue(), (string) $this->divisor->bcValue(), $this->scale?->__invoke());
    }
}
