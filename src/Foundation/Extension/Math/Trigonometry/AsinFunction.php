<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class AsinFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_asin';
    public const UID = 'd39cafef-4c23-472c-855a-75d9909c7afb';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'asin';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return asin($value);
    }

    public static function formula(): string
    {
        return 'f(value) = arcsin(value)';
    }
}
