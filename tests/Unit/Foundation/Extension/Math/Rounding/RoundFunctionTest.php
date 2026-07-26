<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\RoundFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\Value;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;
use RoundingMode;

final class RoundFunctionTest extends TestCase
{
    public function testRoundsToTheNearestIntegerWhenPrecisionIsOmitted(): void
    {
        $function = new RoundFunction(new SpyExpression(4.5));

        self::assertSame(5.0, $function());
    }

    public function testRoundsToTheGivenNumberOfDecimalPlaces(): void
    {
        $function = new RoundFunction(new SpyExpression(3.14159), new SpyExpression(2));

        self::assertSame(3.14, $function());
    }

    public function testDefaultsToPhpRoundHalfUpWhenModeIsOmitted(): void
    {
        $function = new RoundFunction(new SpyExpression(2.5));

        self::assertSame(3.0, $function());
    }

    public function testAcceptsALegacyIntRoundingModeConstant(): void
    {
        $function = new RoundFunction(new SpyExpression(2.5), new Value(0), PHP_ROUND_HALF_DOWN);

        self::assertSame(2.0, $function());
    }

    public function testAcceptsARoundingModeEnumCase(): void
    {
        $function = new RoundFunction(new SpyExpression(2.5), new Value(0), RoundingMode::HalfTowardsZero);

        self::assertSame(2.0, $function());
    }

    public function testAcceptsARoundingModeEnumCaseWithNoLegacyIntEquivalent(): void
    {
        $function = new RoundFunction(new SpyExpression(2.5), new Value(0), RoundingMode::TowardsZero);

        self::assertSame(2.0, $function());
    }
}
