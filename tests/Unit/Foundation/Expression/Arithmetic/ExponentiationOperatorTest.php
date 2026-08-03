<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\ExponentiationOperator;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class ExponentiationOperatorTest extends TestCase
{
    public function testRaisesToPositiveExponent(): void
    {
        $operator = new ExponentiationOperator(new NumericSpyExpression(2), new NumericSpyExpression(3));

        self::assertSame(8, $operator());
    }

    public function testRaisingToZeroReturnsOne(): void
    {
        $operator = new ExponentiationOperator(new NumericSpyExpression(5), new NumericSpyExpression(0));

        self::assertSame(1, $operator());
    }
}
