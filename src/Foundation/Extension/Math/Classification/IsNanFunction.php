<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class IsNanFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_is_nan';
    public const UID = 'a7771811-0910-4de1-a02f-e55a70595ec6';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'is_nan';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): bool
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return is_nan($value);
    }

    public static function formula(): string
    {
        return 'f(value) = value is "not a number"';
    }
}
