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
#[Name('Tanh')]
#[Formula('f(value) = tanh(value)')]
#[Description('Tanh returns the hyperbolic tangent of the given value.')]
class TanhFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_tanh';
    public const UID = 'bd0fea95-e59d-44ce-984d-9bcad107b8c0';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'tanh';

    public function __construct(
        public readonly NumberValue $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        return tanh($value);
    }
}
