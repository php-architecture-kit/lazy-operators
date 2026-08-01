<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('Math | Exponential')]
#[Name('Sqrt')]
#[Formula('f(value) = sqrt(value)')]
#[Description('Sqrt returns the non-negative square root of the given value.')]
class SqrtFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_sqrt';
    public const UID = 'f72be6c9-3d55-49d4-b225-384569218590';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'sqrt';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return sqrt($value);
    }
}
