<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Array\Aggregate;

use PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate\ProductFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class ProductFunctionTest extends TestCase
{
    public function testMultipliesTwoValues(): void
    {
        $function = new ProductFunction(new NumericSpyExpression(2), new NumericSpyExpression(3));

        self::assertSame(6, $function());
    }

    public function testMultipliesSeveralValues(): void
    {
        $function = new ProductFunction(new NumericSpyExpression(1), new NumericSpyExpression(5), new NumericSpyExpression(3), new NumericSpyExpression(-2));

        self::assertSame(-30, $function());
    }

    public function testWorksWithASingleValue(): void
    {
        $function = new ProductFunction(new NumericSpyExpression(7));

        self::assertSame(7, $function());
    }

    public function testMultipliesIntegersAndFloats(): void
    {
        $function = new ProductFunction(new NumericSpyExpression(2), new NumericSpyExpression(1.5));

        self::assertSame(3.0, $function());
    }

    public function testReturnsZeroWhenAnyValueIsZero(): void
    {
        $function = new ProductFunction(new NumericSpyExpression(5), new NumericSpyExpression(0), new NumericSpyExpression(3));

        self::assertSame(0, $function());
    }
}
