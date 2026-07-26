<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class AbsFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_abs';
    public const UID = '4a7e9c1e-2a2b-4643-8b69-563662393f80';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'abs';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return abs($value);
    }

    public static function formula(): string
    {
        return 'f(value) = |value|';
    }
}
