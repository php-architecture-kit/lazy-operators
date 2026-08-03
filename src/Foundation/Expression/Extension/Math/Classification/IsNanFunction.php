<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Classification;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Classification')]
#[Name('Is NaN')]
#[Formula('f(value) = value is "not a number"')]
#[Description('Is NaN returns true when the given value is "not a number".')]
class IsNanFunction implements BooleanValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_is_nan';
    public const UID = 'a7771811-0910-4de1-a02f-e55a70595ec6';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'is_nan';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): bool
    {
        $value = ($this->value)();

        return is_nan($value);
    }
}
