<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class TanFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_tan';
    public const UID = '20050652-d1c6-47aa-8128-9a701a992f51';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'tan';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return tan($value);
    }

    public static function formula(): string
    {
        return 'f(value) = tan(value)';
    }
}
