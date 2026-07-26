<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class DecBinFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_decbin';
    public const UID = 'c8f8452c-5566-4b7e-8481-4274fafac2bd';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'decbin';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): string
    {
        $value = ($this->value)();
        assert(is_int($value));

        return decbin($value);
    }

    public static function formula(): string
    {
        return 'f(value) = binary string representation of value';
    }
}
