<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Trigonometry')]
#[Name('Cos')]
#[Formula('f(value) = cos(value)')]
#[Description('Cos returns the cosine of the given angle, in radians.')]
class CosFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_cos';
    public const UID = 'b45dd649-a927-4248-8661-844754107d94';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'cos';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return cos($value);
    }
}
