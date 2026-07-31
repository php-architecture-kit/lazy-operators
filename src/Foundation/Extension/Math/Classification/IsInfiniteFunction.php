<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class IsInfiniteFunction implements BooleanValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_is_infinite';
    public const UID = '03cc7c90-f984-4349-9021-96c01ff01d99';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'is_infinite';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): bool
    {
        $value = ($this->value)();

        return is_infinite($value);
    }

    public static function formula(): string
    {
        return 'f(value) = value is infinite';
    }
}
