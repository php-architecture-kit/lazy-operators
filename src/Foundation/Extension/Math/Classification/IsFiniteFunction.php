<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class IsFiniteFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_is_finite';
    public const UID = '666b55a0-e0e6-48b7-a961-b730a45fb01c';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'is_finite';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): bool
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return is_finite($value);
    }

    public static function formula(): string
    {
        return 'f(value) = value is a legal finite number';
    }
}
