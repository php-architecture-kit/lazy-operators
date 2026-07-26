<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class HypotFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_hypot';
    public const UID = 'b28fe551-b5b9-4d86-99a5-5b45336cda55';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'hypot';

    public function __construct(
        public readonly Expression $x,
        public readonly Expression $y,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $x = ($this->x)();
        assert(is_int($x) || is_float($x));

        $y = ($this->y)();
        assert(is_int($y) || is_float($y));

        return hypot($x, $y);
    }

    public static function formula(): string
    {
        return 'f(x, y) = length of the hypotenuse of a right triangle with legs x and y';
    }
}
