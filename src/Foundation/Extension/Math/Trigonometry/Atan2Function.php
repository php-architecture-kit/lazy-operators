<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Arc Tangent Of Two Variables')]
#[Formula('f(y, x) = arctan(y / x), using the signs of both to determine the quadrant')]
#[Description('Arc Tangent Of Two Variables returns the angle between the positive x-axis and the point (x, y), using the sign of both to pick the correct quadrant.')]
class Atan2Function implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_atan2';
    public const UID = '4e3d093c-c259-48ec-a6b9-fd79b1297ff2';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'atan2';

    public function __construct(
        public readonly NumberValue $y,
        public readonly NumberValue $x,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $y = ($this->y)();

        $x = ($this->x)();

        return atan2($y, $x);
    }
}
