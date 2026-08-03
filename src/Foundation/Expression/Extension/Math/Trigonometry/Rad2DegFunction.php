<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Trigonometry')]
#[Name('Radians To Degrees')]
#[Formula('f(value) = value in radians converted to degrees')]
#[Description('Radians To Degrees converts the given angle from radians to degrees.')]
class Rad2DegFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_rad2deg';
    public const UID = '2c4249c1-52d4-4e07-89c1-8c8cb0c24fdd';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'rad2deg';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return rad2deg($value);
    }
}
