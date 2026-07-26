<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class IntdivFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_intdiv';
    public const UID = 'f3f4c732-bc81-40c8-bbf7-cb2bccf20e82';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'intdiv';

    public function __construct(
        public readonly Expression $dividend,
        public readonly Expression $divisor,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int
    {
        $dividend = ($this->dividend)();
        assert(is_int($dividend));

        $divisor = ($this->divisor)();
        assert(is_int($divisor));

        return intdiv($dividend, $divisor);
    }

    public static function formula(): string
    {
        return 'f(dividend, divisor) = integer division of dividend by divisor, truncated towards zero';
    }
}
