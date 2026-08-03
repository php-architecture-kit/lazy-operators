<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;

final class UnavailableNativeFunctionExpression implements Expression
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
