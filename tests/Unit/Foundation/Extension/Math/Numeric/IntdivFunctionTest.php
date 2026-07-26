<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Numeric;

use DivisionByZeroError;
use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\IntdivFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class IntdivFunctionTest extends TestCase
{
    public function testComputesIntdivFunction(): void
    {
        $function = new IntdivFunction(new SpyExpression(10), new SpyExpression(3));

        $result = $function();

        self::assertSame(3, $result);
    }

    public function testIntdivByZeroThrowsTheNativeDivisionByZeroError(): void
    {
        $function = new IntdivFunction(new SpyExpression(10), new SpyExpression(0));

        $this->expectException(DivisionByZeroError::class);

        $function();
    }
}
