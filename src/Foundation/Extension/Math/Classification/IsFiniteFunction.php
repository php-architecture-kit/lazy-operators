<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('Math | Classification')]
#[Name('Is Finite')]
#[Formula('f(value) = value is a legal finite number')]
#[Description('Is Finite returns true when the given value is a legal finite number.')]
class IsFiniteFunction implements BooleanValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_is_finite';
    public const UID = '666b55a0-e0e6-48b7-a961-b730a45fb01c';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'is_finite';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): bool
    {
        $value = ($this->value)();

        return is_finite($value);
    }
}
