<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Exponential')]
#[Name('Hypot')]
#[Formula('f(x, y) = length of the hypotenuse of a right triangle with legs x and y')]
#[Description('Hypot returns the length of the hypotenuse of a right triangle with legs x and y, without intermediate overflow or underflow.')]
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
}
