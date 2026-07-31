<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class PowFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_pow';
    public const UID = '98c97d45-cee2-458f-97c5-326664e616ae';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'pow';

    public function __construct(
        public readonly NumberValue $base,
        public readonly NumberValue $exponent,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        $base = ($this->base)();

        $exponent = ($this->exponent)();

        return pow($base, $exponent);
    }

    public static function formula(): string
    {
        return 'f(base, exponent) = base ^ exponent';
    }
}
