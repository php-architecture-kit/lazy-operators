<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\BcMath\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Exception\BcMathFunctionUnavailableException;
use PhpArchitecture\LazyOperators\Tests\Support\UnavailableNativeBcMathFunctionExpression;
use PHPUnit\Framework\TestCase;

final class GuardsNativeFunctionTest extends TestCase
{
    public function testThrowsWhenTheUnderlyingNativeFunctionDoesNotExist(): void
    {
        $this->expectException(BcMathFunctionUnavailableException::class);
        $this->expectExceptionMessage(
            'The "definitely_not_a_real_function()" function required by this Expression is not available in the current PHP build.',
        );

        new UnavailableNativeBcMathFunctionExpression();
    }
}
