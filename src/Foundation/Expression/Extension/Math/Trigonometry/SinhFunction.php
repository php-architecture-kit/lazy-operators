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
#[Name('Sinh')]
#[Formula('f(value) = sinh(value)')]
#[Description('Sinh returns the hyperbolic sine of the given value.')]
class SinhFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_sinh';
    public const UID = 'd8f748ba-69c1-4344-ba6f-1577d455b3e9';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'sinh';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return sinh($value);
    }
}
