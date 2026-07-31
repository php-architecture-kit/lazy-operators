<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use RoundingMode;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Round')]
#[Formula('f(value, precision, mode) = value rounded to precision decimal places, using mode to break ties')]
#[Description('Round rounds the given value to the given number of decimal places, using the given mode to break ties.')]
class RoundFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_round';
    public const UID = '1216ab77-47c0-4c05-89cc-5efc09ea3526';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'round';
    public readonly NumberValue $precision;

    /**
     * @param 1|2|3|4|RoundingMode $mode
     */
    public function __construct(
        public readonly NumberValue $value,
        ?NumberValue $precision = null,
        public readonly int|RoundingMode $mode = PHP_ROUND_HALF_UP,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);

        $this->precision = $precision ?? new IntLiteral(0);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();

        $precision = ($this->precision)();
        assert(is_int($precision));

        return round($value, $precision, $this->mode);
    }
}
