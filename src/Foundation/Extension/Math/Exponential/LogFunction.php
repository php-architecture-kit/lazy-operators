<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\Value;

class LogFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_log';
    public const UID = 'e8b7bced-513f-4050-93ed-7260a150457e';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'log';
    public readonly Expression $base;

    public function __construct(
        public readonly Expression $value,
        ?Expression $base = null,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);

        $this->base = $base ?? new Value(M_E);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        $base = ($this->base)();
        assert(is_int($base) || is_float($base));

        return log($value, $base);
    }

    public static function formula(): string
    {
        return 'f(value, base) = log base "base" of value';
    }
}
