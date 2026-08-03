<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Random')]
#[Name('Mt Get Rand Max')]
#[Formula('f() = the largest possible value returned by mt_rand()')]
#[Description('Mt Get Rand Max returns the largest possible value mt_rand() can return.')]
class MtGetRandMaxFunction implements NumberValue
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
}
