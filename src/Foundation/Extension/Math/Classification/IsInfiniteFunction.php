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
#[Name('Is Infinite')]
#[Formula('f(value) = value is infinite')]
#[Description('Is Infinite returns true when the given value is infinite.')]
class IsInfiniteFunction implements BooleanValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_is_infinite';
    public const UID = '03cc7c90-f984-4349-9021-96c01ff01d99';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'is_infinite';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): bool
    {
        $value = ($this->value)();

        return is_infinite($value);
    }
}
