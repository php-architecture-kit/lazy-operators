<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class SqrtFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_sqrt';
    public const UID = 'f72be6c9-3d55-49d4-b225-384569218590';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'sqrt';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return sqrt($value);
    }

    public static function formula(): string
    {
        return 'f(value) = sqrt(value)';
    }
}
