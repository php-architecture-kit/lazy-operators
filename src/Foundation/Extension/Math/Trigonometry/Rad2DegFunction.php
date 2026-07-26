<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class Rad2DegFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_rad2deg';
    public const UID = '2c4249c1-52d4-4e07-89c1-8c8cb0c24fdd';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'rad2deg';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return rad2deg($value);
    }

    public static function formula(): string
    {
        return 'f(value) = value in radians converted to degrees';
    }
}
