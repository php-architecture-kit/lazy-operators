<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class SinhFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_sinh';
    public const UID = 'd8f748ba-69c1-4344-ba6f-1577d455b3e9';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'sinh';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return sinh($value);
    }

    public static function formula(): string
    {
        return 'f(value) = sinh(value)';
    }
}
