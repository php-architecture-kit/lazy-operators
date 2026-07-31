<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Asinh')]
#[Formula('f(value) = arsinh(value)')]
#[Description('Asinh returns the inverse hyperbolic sine of the given value.')]
class AsinhFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_asinh';
    public const UID = '07eb2371-6976-424b-ba19-96f81a4f5bf5';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'asinh';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return asinh($value);
    }
}
