<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class MtGetRandMaxFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_mt_getrandmax';
    public const UID = 'cef36726-d27f-4661-b6bf-b557e5e82900';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'mt_getrandmax';

    public function __construct()
    {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int
    {
        return mt_getrandmax();
    }

    public static function formula(): string
    {
        return 'f() = the largest possible value returned by mt_rand()';
    }
}
