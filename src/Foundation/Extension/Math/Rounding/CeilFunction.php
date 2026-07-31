<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class CeilFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_ceil';
    public const UID = '70e32741-4271-42b3-aaae-6ee4824497aa';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'ceil';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return ceil($value);
    }

    public static function formula(): string
    {
        return 'f(value) = ceil(value)';
    }
}
