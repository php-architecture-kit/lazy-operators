<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class AcoshFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_acosh';
    public const UID = 'eb66fd12-97bb-4e2f-806e-1d5069c6e7b1';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'acosh';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return acosh($value);
    }

    public static function formula(): string
    {
        return 'f(value) = arcosh(value)';
    }
}
