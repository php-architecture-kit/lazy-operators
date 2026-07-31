<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class SinFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_sin';
    public const UID = '5c51f404-ab36-46b6-8961-c44194ef1bce';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'sin';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return sin($value);
    }

    public static function formula(): string
    {
        return 'f(value) = sin(value)';
    }
}
