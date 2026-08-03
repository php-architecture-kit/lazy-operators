<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('Math | Conversion')]
#[Name('Octal To Decimal')]
#[Formula('f(value) = decimal value of the octal string value')]
#[Description('Octal To Decimal converts an octal string into its decimal value.')]
class OctDecFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_octdec';
    public const UID = 'ab7d58f2-23a1-4097-933d-ee29d258a752';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'octdec';

    public function __construct(
        public readonly StringValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        $value = ($this->value)();

        return octdec($value);
    }
}
