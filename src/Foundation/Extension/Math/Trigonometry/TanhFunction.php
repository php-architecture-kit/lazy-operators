<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class TanhFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_tanh';
    public const UID = 'bd0fea95-e59d-44ce-984d-9bcad107b8c0';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'tanh';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return tanh($value);
    }

    public static function formula(): string
    {
        return 'f(value) = tanh(value)';
    }
}
