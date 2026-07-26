<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class RandFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_rand';
    public const UID = '2eefbb90-e35c-4644-a66e-765514d295b1';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'rand';

    public function __construct(
        public readonly ?Expression $min = null,
        public readonly ?Expression $max = null,
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

    public static function formula(): string
    {
        return 'f(min, max) = a random integer in [min, max], or in the platform default range if omitted';
    }
}
