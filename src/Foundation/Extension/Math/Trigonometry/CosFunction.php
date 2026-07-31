<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class CosFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_cos';
    public const UID = 'b45dd649-a927-4248-8661-844754107d94';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'cos';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return cos($value);
    }

    public static function formula(): string
    {
        return 'f(value) = cos(value)';
    }
}
