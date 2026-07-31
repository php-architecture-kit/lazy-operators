<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class HypotFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_hypot';
    public const UID = 'b28fe551-b5b9-4d86-99a5-5b45336cda55';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'hypot';

    public function __construct(
        public readonly NumberValue $x,
        public readonly NumberValue $y,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $x = ($this->x)();

        $y = ($this->y)();

        return hypot($x, $y);
    }

    public static function formula(): string
    {
        return 'f(x, y) = length of the hypotenuse of a right triangle with legs x and y';
    }
}
