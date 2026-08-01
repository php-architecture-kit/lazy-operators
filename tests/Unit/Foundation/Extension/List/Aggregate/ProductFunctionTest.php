<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\List\Aggregate;

use PhpArchitecture\LazyOperators\Foundation\Extension\List\Aggregate\ProductFunction;
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

    public function testUseBcMathIfAvailableDefaultsToTrue(): void
    {
        $function = new ProductFunction(new NumericSpyExpression(1), new NumericSpyExpression(1));

        self::assertTrue($function->useBcMathIfAvailable);
    }

    /**
     * This dev/CI environment always has ext-bcmath loaded, so without the flag the native fallback
     * path (for environments where ext-bcmath isn't installed, which composer.json only ever
     * "suggests", never requires) would never actually run under test. Toggling
     * useBcMathIfAvailable to false forces that path directly.
     */
    public function testBcMathAvoidsTheFloatingPointDriftTheNativeStrategyExhibits(): void
    {
        $bcMathFunction = new ProductFunction(new NumericSpyExpression(0.1), new NumericSpyExpression(0.2));

        self::assertSame(0.02, $bcMathFunction());

        $nativeFloatFunction = new ProductFunction(new NumericSpyExpression(0.1), new NumericSpyExpression(0.2));
        $nativeFloatFunction->useBcMathIfAvailable = false;

        self::assertSame(0.020000000000000004, $nativeFloatFunction());
    }

    public function testIntegerOnlyProductsSkipBcMathEntirely(): void
    {
        $bcMathFunction = new ProductFunction(new NumericSpyExpression(2), new NumericSpyExpression(3));
        $nativeFloatFunction = new ProductFunction(new NumericSpyExpression(2), new NumericSpyExpression(3));
        $nativeFloatFunction->useBcMathIfAvailable = false;

        self::assertSame(6, $bcMathFunction());
        self::assertSame($nativeFloatFunction(), $bcMathFunction());
    }
}
