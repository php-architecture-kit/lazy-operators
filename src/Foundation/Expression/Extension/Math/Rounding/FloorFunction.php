<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Rounding')]
#[Name('Floor')]
#[Formula('f(value) = floor(value)')]
#[Description('Floor rounds the given value down to the nearest integer.')]
class FloorFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_floor';
    public const UID = 'caa08a2f-d1cc-400e-9f40-d6c16ae567b0';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'floor';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return floor($value);
    }
}
