<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Atanh')]
#[Formula('f(value) = artanh(value)')]
#[Description('Atanh returns the inverse hyperbolic tangent of the given value.')]
class AtanhFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_atanh';
    public const UID = 'c8108c7c-52d8-42af-bc5e-2cb74b541912';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'atanh';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return atanh($value);
    }
}
