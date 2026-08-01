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
#[Name('Sin')]
#[Formula('f(value) = sin(value)')]
#[Description('Sin returns the sine of the given angle, in radians.')]
class SinFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_sin';
    public const UID = '5c51f404-ab36-46b6-8961-c44194ef1bce';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'sin';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return sin($value);
    }
}
