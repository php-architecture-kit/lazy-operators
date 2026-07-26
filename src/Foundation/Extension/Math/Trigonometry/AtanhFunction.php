<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class AtanhFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_atanh';
    public const UID = 'c8108c7c-52d8-42af-bc5e-2cb74b541912';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'atanh';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return atanh($value);
    }

    public static function formula(): string
    {
        return 'f(value) = artanh(value)';
    }
}
