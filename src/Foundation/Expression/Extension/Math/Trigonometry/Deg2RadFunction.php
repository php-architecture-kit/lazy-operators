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
#[Name('Degrees To Radians')]
#[Formula('f(value) = value in degrees converted to radians')]
#[Description('Degrees To Radians converts the given angle from degrees to radians.')]
class Deg2RadFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_deg2rad';
    public const UID = '715bce84-f787-4c71-abd6-4bed689cfee6';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'deg2rad';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return deg2rad($value);
    }
}
