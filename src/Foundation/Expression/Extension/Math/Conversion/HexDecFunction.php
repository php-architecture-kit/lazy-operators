<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Conversion')]
#[Name('Hexadecimal To Decimal')]
#[Formula('f(value) = decimal value of the hexadecimal string value')]
#[Description('Hexadecimal To Decimal converts a hexadecimal string into its decimal value.')]
class HexDecFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_hexdec';
    public const UID = '1d3ce052-4a50-49e8-a091-8d3bbc9696e2';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'hexdec';

    public function __construct(
        public readonly StringValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        $value = ($this->value)();

        return hexdec($value);
    }
}
