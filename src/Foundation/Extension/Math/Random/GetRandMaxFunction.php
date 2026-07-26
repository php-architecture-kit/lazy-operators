<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class GetRandMaxFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_getrandmax';
    public const UID = '7864b13e-ef01-4992-b9f7-8b070c99993d';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'getrandmax';

    public function __construct()
    {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int
    {
        return getrandmax();
    }

    public static function formula(): string
    {
        return 'f() = the largest possible value returned by rand()';
    }
}
