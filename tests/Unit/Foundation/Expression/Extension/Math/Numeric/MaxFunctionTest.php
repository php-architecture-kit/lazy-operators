<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\MaxFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class MaxFunctionTest extends TestCase
{
    public function testReturnsTheGreatestOfTwoValues(): void
    {
        $function = new MaxFunction(new NumericSpyExpression(1), new NumericSpyExpression(5));

        self::assertSame(5, $function());
    }

    public function testReturnsTheGreatestOfSeveralValues(): void
    {
        $function = new MaxFunction(new NumericSpyExpression(1), new NumericSpyExpression(5), new NumericSpyExpression(3), new NumericSpyExpression(-2));

        self::assertSame(5, $function());
    }

    public function testWorksWithASingleValue(): void
    {
        $function = new MaxFunction(new NumericSpyExpression(7));

        self::assertSame(7, $function());
    }
}
