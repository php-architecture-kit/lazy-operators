<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class OctDecFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_octdec';
    public const UID = 'ab7d58f2-23a1-4097-933d-ee29d258a752';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'octdec';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        $value = ($this->value)();
        assert(is_string($value));

        return octdec($value);
    }

    public static function formula(): string
    {
        return 'f(value) = decimal value of the octal string value';
    }
}
