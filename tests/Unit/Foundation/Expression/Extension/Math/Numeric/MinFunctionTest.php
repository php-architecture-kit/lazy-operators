<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\MinFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class MinFunctionTest extends TestCase
{
    public function testReturnsTheSmallestOfTwoValues(): void
    {
        $function = new MinFunction(new NumericSpyExpression(1), new NumericSpyExpression(5));

        self::assertSame(1, $function());
    }

    public function testReturnsTheSmallestOfSeveralValues(): void
    {
        $function = new MinFunction(new NumericSpyExpression(1), new NumericSpyExpression(5), new NumericSpyExpression(3), new NumericSpyExpression(-2));

        self::assertSame(-2, $function());
    }

    public function testWorksWithASingleValue(): void
    {
        $function = new MinFunction(new NumericSpyExpression(7));

        self::assertSame(7, $function());
    }
}
