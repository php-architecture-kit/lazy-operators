<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Mt Rand')]
#[Formula('f(min, max) = a random integer in [min, max], or in the platform default range if omitted')]
#[Description('Mt Rand returns a random integer between the given minimum and maximum using the Mersenne Twister generator, or from the platform default range when no bounds are given.')]
class MtRandFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_mt_rand';
    public const UID = '1837637b-a064-4df0-99cb-5ef9e00e27ab';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'mt_rand';

    public function __construct(
        public readonly ?NumberValue $min = null,
        public readonly ?NumberValue $max = null,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int
    {
        if ($this->min === null && $this->max === null) {
            return mt_rand();
        }

        assert($this->min !== null && $this->max !== null);

        $min = ($this->min)();
        assert(is_int($min));

        $max = ($this->max)();
        assert(is_int($max));

        return mt_rand($min, $max);
    }
}
