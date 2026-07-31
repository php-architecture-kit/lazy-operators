<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Random Int')]
#[Formula('f(min, max) = a cryptographically secure random integer in [min, max]')]
#[Description('Random Int returns a cryptographically secure random integer between the given minimum and maximum.')]
class RandomIntFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_random_int';
    public const UID = '597dc252-306a-435e-914c-4dc4ec6d9deb';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'random_int';

    public function __construct(
        public readonly NumberValue $min,
        public readonly NumberValue $max,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int
    {
        $min = ($this->min)();
        assert(is_int($min));

        $max = ($this->max)();
        assert(is_int($max));

        return random_int($min, $max);
    }
}
