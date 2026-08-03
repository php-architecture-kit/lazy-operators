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
#[Name('Cosh')]
#[Formula('f(value) = cosh(value)')]
#[Description('Cosh returns the hyperbolic cosine of the given value.')]
class CoshFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_cosh';
    public const UID = '6af9a5aa-a877-45cf-8093-1055c2d0df1a';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'cosh';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return cosh($value);
    }
}
