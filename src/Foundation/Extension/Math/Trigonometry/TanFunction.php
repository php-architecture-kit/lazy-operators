<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Tan')]
#[Formula('f(value) = tan(value)')]
#[Description('Tan returns the tangent of the given angle, in radians.')]
class TanFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_tan';
    public const UID = '20050652-d1c6-47aa-8128-9a701a992f51';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'tan';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return tan($value);
    }
}
