<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\Value;
use RoundingMode;

class RoundFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_round';
    public const UID = '1216ab77-47c0-4c05-89cc-5efc09ea3526';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'round';
    public readonly Expression $precision;

    /**
     * @param 1|2|3|4|RoundingMode $mode
     */
    public function __construct(
        public readonly Expression $value,
        ?Expression $precision = null,
        public readonly int|RoundingMode $mode = PHP_ROUND_HALF_UP,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);

        $this->precision = $precision ?? new Value(0);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        $precision = ($this->precision)();
        assert(is_int($precision));

        return round($value, $precision, $this->mode);
    }

    public static function formula(): string
    {
        return 'f(value, precision, mode) = value rounded to precision decimal places, using mode to break ties';
    }
}
