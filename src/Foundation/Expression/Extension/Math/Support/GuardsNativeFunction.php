<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exception\MathFunctionUnavailableException;

trait GuardsNativeFunction
{
    private static function guardAvailable(string $function): void
    {
        if (!function_exists($function)) {
            throw MathFunctionUnavailableException::create($function);
        }
    }
}
