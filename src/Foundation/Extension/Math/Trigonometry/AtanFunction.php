<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('Math | Trigonometry')]
#[Name('Atan')]
#[Formula('f(value) = arctan(value)')]
#[Description('Atan returns the inverse tangent of the given value, in radians.')]
class AtanFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_atan';
    public const UID = 'b6994fd4-d119-4189-a8ef-2d909032b628';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'atan';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return atan($value);
    }
}
