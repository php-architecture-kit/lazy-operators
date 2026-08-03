<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\MultiplicationOperator;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class MultiplicationOperatorTest extends TestCase
{
    public function testMultipliesPositiveOperands(): void
    {
        $operator = new MultiplicationOperator(new NumericSpyExpression(4), new NumericSpyExpression(3));

        self::assertSame(12, $operator());
    }

    public function testMultiplyingByZeroReturnsZero(): void
    {
        $operator = new MultiplicationOperator(new NumericSpyExpression(4), new NumericSpyExpression(0));

        self::assertSame(0, $operator());
    }
}
