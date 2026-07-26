<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ExponentiationOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class ExponentiationOperatorTest extends TestCase
{
    public function testRaisesToPositiveExponent(): void
    {
        $operator = new ExponentiationOperator(new SpyExpression(2), new SpyExpression(3));

        self::assertSame(8, $operator());
    }

    public function testRaisingToZeroReturnsOne(): void
    {
        $operator = new ExponentiationOperator(new SpyExpression(5), new SpyExpression(0));

        self::assertSame(1, $operator());
    }
}
