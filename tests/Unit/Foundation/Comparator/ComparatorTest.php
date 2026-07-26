<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Comparator\Comparator;
use PhpArchitecture\LazyOperators\Foundation\Comparator\SpaceshipOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class ComparatorTest extends TestCase
{
    public function testOfWrapsARawValue(): void
    {
        self::assertSame(2, Comparator::of(2)->build()());
    }

    public function testOfAcceptsAnExpressionDirectly(): void
    {
        self::assertSame(2, Comparator::of(new SpyExpression(2))->build()());
    }

    public function testBuildReturnsAnExpressionNotComparator(): void
    {
        $built = Comparator::of(1)->spaceship(2)->build();

        self::assertInstanceOf(Expression::class, $built);
        self::assertNotInstanceOf(Comparator::class, $built);
    }

    public function testSpaceshipProducesASpaceshipOperator(): void
    {
        self::assertInstanceOf(SpaceshipOperator::class, Comparator::of(1)->spaceship(2)->build());
    }

    public function testEvaluatesCorrectly(): void
    {
        self::assertSame(-1, Comparator::of(1)->spaceship(2)->build()());
        self::assertSame(0, Comparator::of(2)->spaceship(2)->build()());
        self::assertSame(1, Comparator::of(3)->spaceship(2)->build()());
    }

    public function testOperandsAcceptExpressionInstancesDirectly(): void
    {
        $right = new SpyExpression(2);

        $expr = Comparator::of(1)->spaceship($right)->build();

        self::assertSame(-1, $expr());
        self::assertSame(1, $right->invocations);
    }
}
