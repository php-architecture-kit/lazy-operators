<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class Deg2RadFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_deg2rad';
    public const UID = '715bce84-f787-4c71-abd6-4bed689cfee6';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'deg2rad';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return deg2rad($value);
    }

    public static function formula(): string
    {
        return 'f(value) = value in degrees converted to radians';
    }
}
