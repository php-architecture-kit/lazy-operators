<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class FmodFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_fmod';
    public const UID = 'e0d79c61-a5ec-49db-bb71-12bef8787333';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'fmod';

    public function __construct(
        public readonly NumberValue $dividend,
        public readonly NumberValue $divisor,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $dividend = ($this->dividend)();

        $divisor = ($this->divisor)();

        return fmod($dividend, $divisor);
    }

    public static function formula(): string
    {
        return 'f(dividend, divisor) = floating point remainder of dividend / divisor';
    }
}
