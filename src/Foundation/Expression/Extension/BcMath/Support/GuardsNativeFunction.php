<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Exception\BcMathFunctionUnavailableException;

trait GuardsNativeFunction
{
    private static function guardAvailable(string $function): void
    {
        if (!function_exists($function)) {
            throw BcMathFunctionUnavailableException::create($function);
        }
    }
}
