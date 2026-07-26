<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class FmodFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_fmod';
    public const UID = 'e0d79c61-a5ec-49db-bb71-12bef8787333';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'fmod';

    public function __construct(
        public readonly Expression $dividend,
        public readonly Expression $divisor,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $dividend = ($this->dividend)();
        assert(is_int($dividend) || is_float($dividend));

        $divisor = ($this->divisor)();
        assert(is_int($divisor) || is_float($divisor));

        return fmod($dividend, $divisor);
    }

    public static function formula(): string
    {
        return 'f(dividend, divisor) = floating point remainder of dividend / divisor';
    }
}
