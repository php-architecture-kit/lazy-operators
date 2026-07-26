<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class Log10Function implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_log10';
    public const UID = '86ee12a8-68fa-400c-9483-15de61e85682';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'log10';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return log10($value);
    }

    public static function formula(): string
    {
        return 'f(value) = log base 10 of value';
    }
}
