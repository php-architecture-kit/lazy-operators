<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('LCG Value')]
#[Formula('f() = a pseudo-random float in [0, 1)')]
#[Description('LCG Value returns a pseudo-random float between 0 (inclusive) and 1 (exclusive).')]
class LcgValueFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_lcg_value';
    public const UID = 'ffb9a443-793c-4c60-b9a5-1899b26d8a57';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'lcg_value';

    public function __construct()
    {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        return lcg_value();
    }
}
