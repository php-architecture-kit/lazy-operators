<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class Expm1Function implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_expm1';
    public const UID = '1f928c65-3554-4755-9cf5-a8debf1d1ab9';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'expm1';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return expm1($value);
    }

    public static function formula(): string
    {
        return 'f(value) = e^value - 1';
    }
}
