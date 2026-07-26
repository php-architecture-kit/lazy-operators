<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use DivisionByZeroError;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\DivisionOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class DivisionOperatorTest extends TestCase
{
    public function testDividesTwoNumbers(): void
    {
        $operator = new DivisionOperator(new SpyExpression(9), new SpyExpression(2));

        self::assertSame(4.5, $operator());
    }

    public function testDivisionByZeroThrows(): void
    {
        $operator = new DivisionOperator(new SpyExpression(1), new SpyExpression(0));

        $this->expectException(DivisionByZeroError::class);

        $operator();
    }
}
