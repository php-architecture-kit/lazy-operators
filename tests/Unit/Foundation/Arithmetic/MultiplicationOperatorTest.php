<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\MultiplicationOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class MultiplicationOperatorTest extends TestCase
{
    public function testMultipliesPositiveOperands(): void
    {
        $operator = new MultiplicationOperator(new SpyExpression(4), new SpyExpression(3));

        self::assertSame(12, $operator());
    }

    public function testMultiplyingByZeroReturnsZero(): void
    {
        $operator = new MultiplicationOperator(new SpyExpression(4), new SpyExpression(0));

        self::assertSame(0, $operator());
    }
}
