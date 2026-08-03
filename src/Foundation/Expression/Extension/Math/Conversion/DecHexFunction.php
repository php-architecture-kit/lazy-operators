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
#[Name('Decimal To Hexadecimal')]
#[Formula('f(value) = hexadecimal string representation of value')]
#[Description('Decimal To Hexadecimal converts a decimal number into its hexadecimal string representation.')]
class DecHexFunction implements StringValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_dechex';
    public const UID = '801e1a61-a475-48c0-bfa8-e969a81f3036';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'dechex';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): string
    {
        $value = ($this->value)();
        assert(is_int($value));

        return dechex($value);
    }
}
