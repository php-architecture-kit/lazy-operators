<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('Math | Numeric')]
#[Name('Abs')]
#[Formula('f(value) = |value|')]
#[Description('Abs returns the given number with any negative sign removed.')]
class AbsFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_abs';
    public const UID = '4a7e9c1e-2a2b-4643-8b69-563662393f80';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'abs';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        $value = ($this->value)();

        return abs($value);
    }
}
