<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class LcgValueFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_lcg_value';
    public const UID = 'ffb9a443-793c-4c60-b9a5-1899b26d8a57';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'lcg_value';

    public function __construct()
    {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        return lcg_value();
    }

    public static function formula(): string
    {
        return 'f() = a pseudo-random float in [0, 1)';
    }
}
