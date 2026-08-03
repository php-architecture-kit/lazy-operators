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
#[Name('Log1p')]
#[Formula('f(value) = log(1 + value)')]
#[Description('Log1p returns the natural logarithm of one plus the given value, with better precision than Log for values near zero.')]
class Log1pFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_log1p';
    public const UID = '413b7bbc-a2f3-49f3-aa8c-94e036f6961b';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'log1p';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return log1p($value);
    }
}
