<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class MtRandFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_mt_rand';
    public const UID = '1837637b-a064-4df0-99cb-5ef9e00e27ab';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'mt_rand';

    public function __construct(
        public readonly ?Expression $min = null,
        public readonly ?Expression $max = null,
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

    public static function formula(): string
    {
        return 'f(min, max) = a random integer in [min, max], or in the platform default range if omitted';
    }
}
