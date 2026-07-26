<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class ExpFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_exp';
    public const UID = 'c415d67c-347f-4411-b2b5-3aef793a94a9';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'exp';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return exp($value);
    }

    public static function formula(): string
    {
        return 'f(value) = e^value';
    }
}
