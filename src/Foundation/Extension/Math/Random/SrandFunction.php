<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;

class SrandFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_srand';
    public const UID = 'e0884733-486f-4768-a3f7-0c17461f8735';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'srand';
    public readonly NumberValue $seed;

    public function __construct(
        ?NumberValue $seed = null,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);

        $this->seed = $seed ?? new IntLiteral(0);
    }

    public function __invoke(): null
    {
        $seed = ($this->seed)();
        assert(is_int($seed));

        srand($seed);

        return null;
    }

    public static function formula(): string
    {
        return 'f(seed) = seeds the rand() generator with seed; has no return value';
    }
}
