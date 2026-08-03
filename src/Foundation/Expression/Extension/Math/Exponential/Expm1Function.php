<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Exponential')]
#[Name('Expm1')]
#[Formula('f(value) = e^value - 1')]
#[Description('Expm1 returns e raised to the power of the given value, minus one, with better precision than Exp for values near zero.')]
class Expm1Function implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_expm1';
    public const UID = '1f928c65-3554-4755-9cf5-a8debf1d1ab9';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'expm1';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return expm1($value);
    }
}
