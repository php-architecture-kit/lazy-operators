<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class ExpFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_exp';
    public const UID = 'c415d67c-347f-4411-b2b5-3aef793a94a9';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'exp';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return exp($value);
    }

    public static function formula(): string
    {
        return 'f(value) = e^value';
    }
}
