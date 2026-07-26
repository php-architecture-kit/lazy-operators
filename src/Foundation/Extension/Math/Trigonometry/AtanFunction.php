<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class AtanFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_atan';
    public const UID = 'b6994fd4-d119-4189-a8ef-2d909032b628';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'atan';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return atan($value);
    }

    public static function formula(): string
    {
        return 'f(value) = arctan(value)';
    }
}
