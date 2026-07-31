<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Acosh')]
#[Formula('f(value) = arcosh(value)')]
#[Description('Acosh returns the inverse hyperbolic cosine of the given value.')]
class AcoshFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_acosh';
    public const UID = 'eb66fd12-97bb-4e2f-806e-1d5069c6e7b1';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'acosh';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return acosh($value);
    }
}
