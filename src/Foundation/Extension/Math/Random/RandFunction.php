<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Rand')]
#[Formula('f(min, max) = a random integer in [min, max], or in the platform default range if omitted')]
#[Description('Rand returns a random integer between the given minimum and maximum, or from the platform default range when no bounds are given.')]
class RandFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_rand';
    public const UID = '2eefbb90-e35c-4644-a66e-765514d295b1';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'rand';

    public function __construct(
        public readonly ?NumberValue $min = null,
        public readonly ?NumberValue $max = null,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int
    {
        if ($this->min === null && $this->max === null) {
            return rand();
        }

        assert($this->min !== null && $this->max !== null);

        $min = ($this->min)();
        assert(is_int($min));

        $max = ($this->max)();
        assert(is_int($max));

        return rand($min, $max);
    }
}
