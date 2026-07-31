<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Mt Srand')]
#[Formula('f(seed, mode) = seeds the mt_rand() generator with seed using algorithm mode; has no return value')]
#[Description('Mt Srand seeds the Mersenne Twister random number generator with the given seed and algorithm mode; it has no return value.')]
class MtSrandFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_mt_srand';
    public const UID = '1f4095f4-d6bb-4b9f-8b00-464ab0451d8c';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'mt_srand';
    public readonly NumberValue $seed;

    public function __construct(
        ?NumberValue $seed = null,
        public readonly int $mode = MT_RAND_MT19937,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);

        $this->seed = $seed ?? new IntLiteral(0);
    }

    public function __invoke(): null
    {
        $seed = ($this->seed)();
        assert(is_int($seed));

        mt_srand($seed, $this->mode);

        return null;
    }
}
