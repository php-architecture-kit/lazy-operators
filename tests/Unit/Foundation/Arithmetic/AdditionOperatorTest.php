<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\AdditionOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class AdditionOperatorTest extends TestCase
{
    public function testAddsTwoIntegers(): void
    {
        $operator = new AdditionOperator(new SpyExpression(2), new SpyExpression(3));

        self::assertSame(5, $operator());
    }

    public function testAddsIntegerAndFloat(): void
    {
        $operator = new AdditionOperator(new SpyExpression(2), new SpyExpression(1.5));

        self::assertSame(3.5, $operator());
    }

    public function testAddsNegativeOperands(): void
    {
        $operator = new AdditionOperator(new SpyExpression(-2), new SpyExpression(-3));

        self::assertSame(-5, $operator());
    }
}
