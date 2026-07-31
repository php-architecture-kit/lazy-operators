<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\AdditionOperator;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class AdditionOperatorTest extends TestCase
{
    public function testAddsTwoIntegers(): void
    {
        $operator = new AdditionOperator(new NumericSpyExpression(2), new NumericSpyExpression(3));

        self::assertSame(5, $operator());
    }

    public function testAddsIntegerAndFloat(): void
    {
        $operator = new AdditionOperator(new NumericSpyExpression(2), new NumericSpyExpression(1.5));

        self::assertSame(3.5, $operator());
    }

    public function testAddsNegativeOperands(): void
    {
        $operator = new AdditionOperator(new NumericSpyExpression(-2), new NumericSpyExpression(-3));

        self::assertSame(-5, $operator());
    }
}
