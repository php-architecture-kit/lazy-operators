<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class AcosFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_acos';
    public const UID = 'cca18ea4-26e3-45b1-b522-74f35d8fd3ff';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'acos';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return acos($value);
    }

    public static function formula(): string
    {
        return 'f(value) = arccos(value)';
    }
}
