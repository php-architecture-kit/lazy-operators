<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Decimal To Octal')]
#[Formula('f(value) = octal string representation of value')]
#[Description('Decimal To Octal converts a decimal number into its octal string representation.')]
class DecOctFunction implements StringValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_decoct';
    public const UID = '2a9890a1-c286-4abb-a24e-4c261cc79644';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'decoct';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): string
    {
        $value = ($this->value)();
        assert(is_int($value));

        return decoct($value);
    }
}
