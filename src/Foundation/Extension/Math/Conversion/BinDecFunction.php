<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Binary To Decimal')]
#[Formula('f(value) = decimal value of the binary string value')]
#[Description('Binary To Decimal converts a binary string into its decimal value.')]
class BinDecFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_bindec';
    public const UID = '0811b4cf-93dc-41da-b945-5b0ae7a14521';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bindec';

    public function __construct(
        public readonly StringValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        $value = ($this->value)();

        return bindec($value);
    }
}
