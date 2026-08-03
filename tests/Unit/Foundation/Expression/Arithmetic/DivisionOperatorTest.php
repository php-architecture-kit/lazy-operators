<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use DivisionByZeroError;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\DivisionOperator;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class DivisionOperatorTest extends TestCase
{
    public function testDividesTwoNumbers(): void
    {
        $operator = new DivisionOperator(new NumericSpyExpression(9), new NumericSpyExpression(2));

        self::assertSame(4.5, $operator());
    }

    public function testDivisionByZeroThrows(): void
    {
        $operator = new DivisionOperator(new NumericSpyExpression(1), new NumericSpyExpression(0));

        $this->expectException(DivisionByZeroError::class);

        $operator();
    }
}
