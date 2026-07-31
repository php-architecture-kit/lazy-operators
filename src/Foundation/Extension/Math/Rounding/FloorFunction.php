<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class FloorFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_floor';
    public const UID = 'caa08a2f-d1cc-400e-9f40-d6c16ae567b0';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'floor';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return floor($value);
    }

    public static function formula(): string
    {
        return 'f(value) = floor(value)';
    }
}
