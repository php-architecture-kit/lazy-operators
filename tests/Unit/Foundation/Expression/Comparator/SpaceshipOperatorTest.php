<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Expression\Comparator\SpaceshipOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class SpaceshipOperatorTest extends TestCase
{
    public function testReturnsNegativeOneWhenLeftIsSmaller(): void
    {
        $operator = new SpaceshipOperator(new SpyExpression(1), new SpyExpression(2));

        self::assertSame(-1, $operator());
    }

    public function testReturnsZeroWhenEqual(): void
    {
        $operator = new SpaceshipOperator(new SpyExpression(2), new SpyExpression(2));

        self::assertSame(0, $operator());
    }

    public function testReturnsOneWhenLeftIsGreater(): void
    {
        $operator = new SpaceshipOperator(new SpyExpression(3), new SpyExpression(2));

        self::assertSame(1, $operator());
    }
}
