<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class IsNanFunction implements BooleanValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_is_nan';
    public const UID = 'a7771811-0910-4de1-a02f-e55a70595ec6';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'is_nan';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): bool
    {
        $value = ($this->value)();

        return is_nan($value);
    }

    public static function formula(): string
    {
        return 'f(value) = value is "not a number"';
    }
}
