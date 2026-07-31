<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Log10')]
#[Formula('f(value) = log base 10 of value')]
#[Description('Log10 returns the base-10 logarithm of the given value.')]
class Log10Function implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_log10';
    public const UID = '86ee12a8-68fa-400c-9483-15de61e85682';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'log10';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return log10($value);
    }
}
