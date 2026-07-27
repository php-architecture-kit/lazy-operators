<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Array\Aggregate;

use PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate\SumFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class SumFunctionTest extends TestCase
{
    public function testSumsTwoValues(): void
    {
        $function = new SumFunction(new SpyExpression(2), new SpyExpression(3));

        self::assertSame(5, $function());
    }

    public function testSumsSeveralValues(): void
    {
        $function = new SumFunction(new SpyExpression(1), new SpyExpression(5), new SpyExpression(3), new SpyExpression(-2));

        self::assertSame(7, $function());
    }

    public function testWorksWithASingleValue(): void
    {
        $function = new SumFunction(new SpyExpression(7));

        self::assertSame(7, $function());
    }

    public function testSumsIntegersAndFloats(): void
    {
        $function = new SumFunction(new SpyExpression(2), new SpyExpression(1.5));

        self::assertSame(3.5, $function());
    }
}
