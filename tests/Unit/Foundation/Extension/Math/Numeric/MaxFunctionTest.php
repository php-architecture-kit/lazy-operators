<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\MaxFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class MaxFunctionTest extends TestCase
{
    public function testReturnsTheGreatestOfTwoValues(): void
    {
        $function = new MaxFunction(new SpyExpression(1), new SpyExpression(5));

        self::assertSame(5, $function());
    }

    public function testReturnsTheGreatestOfSeveralValues(): void
    {
        $function = new MaxFunction(new SpyExpression(1), new SpyExpression(5), new SpyExpression(3), new SpyExpression(-2));

        self::assertSame(5, $function());
    }

    public function testWorksWithASingleValue(): void
    {
        $function = new MaxFunction(new SpyExpression(7));

        self::assertSame(7, $function());
    }
}
