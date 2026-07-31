<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Array\Aggregate;

use PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate\SumFunction;
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
}
