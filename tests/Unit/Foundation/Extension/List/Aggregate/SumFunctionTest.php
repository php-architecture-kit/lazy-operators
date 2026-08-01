<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\List\Aggregate;

use PhpArchitecture\LazyOperators\Foundation\Extension\List\Aggregate\SumFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class SumFunctionTest extends TestCase
{
    public function testSumsTwoValues(): void
    {
        $function = new SumFunction(new NumericSpyExpression(2), new NumericSpyExpression(3));

        self::assertSame(5, $function());
    }

    public function testSumsSeveralValues(): void
    {
        $function = new SumFunction(new NumericSpyExpression(1), new NumericSpyExpression(5), new NumericSpyExpression(3), new NumericSpyExpression(-2));

        self::assertSame(7, $function());
    }

    public function testWorksWithASingleValue(): void
    {
        $function = new SumFunction(new NumericSpyExpression(7));

        self::assertSame(7, $function());
    }

    public function testSumsIntegersAndFloats(): void
    {
        $function = new SumFunction(new NumericSpyExpression(2), new NumericSpyExpression(1.5));

        self::assertSame(3.5, $function());
    }

    public function testUseBcMathIfAvailableDefaultsToTrue(): void
    {
        $function = new SumFunction(new NumericSpyExpression(1), new NumericSpyExpression(1));

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
        $bcMathFunction = new SumFunction(new NumericSpyExpression(0.1), new NumericSpyExpression(0.2));

        self::assertSame(0.3, $bcMathFunction());

        $nativeFloatFunction = new SumFunction(new NumericSpyExpression(0.1), new NumericSpyExpression(0.2));
        $nativeFloatFunction->useBcMathIfAvailable = false;

        self::assertSame(0.30000000000000004, $nativeFloatFunction());
    }

    public function testIntegerOnlySumsSkipBcMathEntirely(): void
    {
        $bcMathFunction = new SumFunction(new NumericSpyExpression(2), new NumericSpyExpression(3));
        $nativeFloatFunction = new SumFunction(new NumericSpyExpression(2), new NumericSpyExpression(3));
        $nativeFloatFunction->useBcMathIfAvailable = false;

        self::assertSame(5, $bcMathFunction());
        self::assertSame($nativeFloatFunction(), $bcMathFunction());
    }
}
