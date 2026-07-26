<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Support;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exception\MathFunctionUnavailableException;
use PhpArchitecture\LazyOperators\Tests\Support\UnavailableNativeFunctionExpression;
use PHPUnit\Framework\TestCase;

final class GuardsNativeFunctionTest extends TestCase
{
    public function testThrowsWhenTheUnderlyingNativeFunctionDoesNotExist(): void
    {
        $this->expectException(MathFunctionUnavailableException::class);
        $this->expectExceptionMessage(
            'The "definitely_not_a_real_function()" function required by this Expression is not available in the current PHP build.',
        );

        new UnavailableNativeFunctionExpression();
    }
}
