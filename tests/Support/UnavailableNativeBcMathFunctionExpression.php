<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\GuardsNativeFunction;

final class UnavailableNativeBcMathFunctionExpression implements Expression
{
    use GuardsNativeFunction;

    public function __construct()
    {
        self::guardAvailable('definitely_not_a_real_function');
    }

    public function __invoke(): mixed
    {
        return null;
    }
}
