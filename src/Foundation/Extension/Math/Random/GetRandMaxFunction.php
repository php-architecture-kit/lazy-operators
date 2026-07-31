<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Get Rand Max')]
#[Formula('f() = the largest possible value returned by rand()')]
#[Description('Get Rand Max returns the largest possible value rand() can return on the current platform.')]
class GetRandMaxFunction implements NumberValue
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
}
