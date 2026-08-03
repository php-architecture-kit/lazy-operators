<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Numeric')]
#[Name('Fdiv')]
#[Formula('f(dividend, divisor) = dividend / divisor (IEEE-754 safe: no exception on division by zero)')]
#[Description('Fdiv divides the dividend by the divisor under IEEE-754 rules, returning INF, -INF, or NAN instead of throwing when the divisor is zero.')]
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
}
