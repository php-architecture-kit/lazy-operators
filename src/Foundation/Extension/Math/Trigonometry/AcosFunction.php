<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Acos')]
#[Formula('f(value) = arccos(value)')]
#[Description('Acos returns the inverse cosine of the given value, in radians.')]
class AcosFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_acos';
    public const UID = 'cca18ea4-26e3-45b1-b522-74f35d8fd3ff';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'acos';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return acos($value);
    }
}
