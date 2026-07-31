<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Pi')]
#[Formula('f() = pi')]
#[Description('Pi returns the value of the mathematical constant pi.')]
class PiFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_pi';
    public const UID = '23aeefd9-7667-4e45-a607-09c48fa282a1';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'pi';

    public function __construct()
    {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        return pi();
    }
}
