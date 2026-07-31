<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class FdivFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_fdiv';
    public const UID = '94fb1517-17d2-44aa-aa3a-233f47f60006';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'fdiv';

    public function __construct(
        public readonly NumberValue $dividend,
        public readonly NumberValue $divisor,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $dividend = ($this->dividend)();

        $divisor = ($this->divisor)();

        return fdiv($dividend, $divisor);
    }

    public static function formula(): string
    {
        return 'f(dividend, divisor) = dividend / divisor (IEEE-754 safe: no exception on division by zero)';
    }
}
