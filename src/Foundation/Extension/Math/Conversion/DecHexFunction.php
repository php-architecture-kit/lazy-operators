<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class DecHexFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_dechex';
    public const UID = '801e1a61-a475-48c0-bfa8-e969a81f3036';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'dechex';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): string
    {
        $value = ($this->value)();
        assert(is_int($value));

        return dechex($value);
    }

    public static function formula(): string
    {
        return 'f(value) = hexadecimal string representation of value';
    }
}
