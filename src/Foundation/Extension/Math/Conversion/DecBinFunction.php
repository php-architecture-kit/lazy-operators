<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Decimal To Binary')]
#[Formula('f(value) = binary string representation of value')]
#[Description('Decimal To Binary converts a decimal number into its binary string representation.')]
class DecBinFunction implements StringValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_decbin';
    public const UID = 'c8f8452c-5566-4b7e-8481-4274fafac2bd';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'decbin';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): string
    {
        $value = ($this->value)();
        assert(is_int($value));

        return decbin($value);
    }
}
