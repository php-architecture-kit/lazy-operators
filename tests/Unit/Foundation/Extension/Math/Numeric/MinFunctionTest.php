<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\MinFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class MinFunctionTest extends TestCase
{
    public function testReturnsTheSmallestOfTwoValues(): void
    {
        $function = new MinFunction(new SpyExpression(1), new SpyExpression(5));

        self::assertSame(1, $function());
    }

    public function testReturnsTheSmallestOfSeveralValues(): void
    {
        $function = new MinFunction(new SpyExpression(1), new SpyExpression(5), new SpyExpression(3), new SpyExpression(-2));

        self::assertSame(-2, $function());
    }

    public function testWorksWithASingleValue(): void
    {
        $function = new MinFunction(new SpyExpression(7));

        self::assertSame(7, $function());
    }
}
