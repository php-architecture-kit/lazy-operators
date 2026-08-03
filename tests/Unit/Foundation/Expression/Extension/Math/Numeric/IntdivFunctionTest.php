<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Numeric;

use DivisionByZeroError;
use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\IntdivFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class IntdivFunctionTest extends TestCase
{
    public function testComputesIntdivFunction(): void
    {
        $function = new IntdivFunction(new NumericSpyExpression(10), new NumericSpyExpression(3));

        $result = $function();

        self::assertSame(3, $result);
    }

    public function testIntdivByZeroThrowsTheNativeDivisionByZeroError(): void
    {
        $function = new IntdivFunction(new NumericSpyExpression(10), new NumericSpyExpression(0));

        $this->expectException(DivisionByZeroError::class);

        $function();
    }
}
