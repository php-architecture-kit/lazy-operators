<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class HexDecFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_hexdec';
    public const UID = '1d3ce052-4a50-49e8-a091-8d3bbc9696e2';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'hexdec';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        $value = ($this->value)();
        assert(is_string($value));

        return hexdec($value);
    }

    public static function formula(): string
    {
        return 'f(value) = decimal value of the hexadecimal string value';
    }
}
