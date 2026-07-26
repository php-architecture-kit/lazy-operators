<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class CoshFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_cosh';
    public const UID = '6af9a5aa-a877-45cf-8093-1055c2d0df1a';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'cosh';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return cosh($value);
    }

    public static function formula(): string
    {
        return 'f(value) = cosh(value)';
    }
}
