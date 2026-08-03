<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\Comparison;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\EqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\GreaterThanOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\GreaterThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\IdenticalOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\LessThanOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\LessThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\NotEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\NotIdenticalOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class ComparisonTest extends TestCase
{
    public function testOfWrapsARawValue(): void
    {
        self::assertSame(2, Comparison::of(2)->build()());
    }

    public function testOfAcceptsAnExpressionDirectly(): void
    {
        self::assertSame(2, Comparison::of(new SpyExpression(2))->build()());
    }

    public function testBuildReturnsAnExpressionNotComparison(): void
    {
        $built = Comparison::of(1)->equal(1)->build();

        self::assertInstanceOf(Expression::class, $built);
        self::assertNotInstanceOf(Comparison::class, $built);
    }

    public function testEqualProducesAnEqualOperator(): void
    {
        self::assertInstanceOf(EqualOperator::class, Comparison::of(1)->equal(1)->build());
    }

    public function testNotEqualProducesANotEqualOperator(): void
    {
        self::assertInstanceOf(NotEqualOperator::class, Comparison::of(1)->notEqual(2)->build());
    }

    public function testIdenticalProducesAnIdenticalOperator(): void
    {
        self::assertInstanceOf(IdenticalOperator::class, Comparison::of(1)->identical(1)->build());
    }

    public function testNotIdenticalProducesANotIdenticalOperator(): void
    {
        self::assertInstanceOf(NotIdenticalOperator::class, Comparison::of(1)->notIdentical('1')->build());
    }

    public function testGreaterThanProducesAGreaterThanOperator(): void
    {
        self::assertInstanceOf(GreaterThanOperator::class, Comparison::of(2)->greaterThan(1)->build());
    }

    public function testGreaterThanOrEqualProducesAGreaterThanOrEqualOperator(): void
    {
        self::assertInstanceOf(GreaterThanOrEqualOperator::class, Comparison::of(2)->greaterThanOrEqual(2)->build());
    }

    public function testLessThanProducesALessThanOperator(): void
    {
        self::assertInstanceOf(LessThanOperator::class, Comparison::of(1)->lessThan(2)->build());
    }

    public function testLessThanOrEqualProducesALessThanOrEqualOperator(): void
    {
        self::assertInstanceOf(LessThanOrEqualOperator::class, Comparison::of(2)->lessThanOrEqual(2)->build());
    }

    public function testEvaluatesCorrectly(): void
    {
        $expr = Comparison::of(5)->greaterThan(3)->build();

        self::assertTrue($expr());
    }

    public function testLooseEqualityAcceptsMixedTypes(): void
    {
        $expr = Comparison::of('1')->equal(1)->build();

        self::assertTrue($expr());
    }

    public function testStrictEqualityDistinguishesTypes(): void
    {
        $expr = Comparison::of('1')->identical(1)->build();

        self::assertFalse($expr());
    }

    public function testOperandsAcceptExpressionInstancesDirectly(): void
    {
        $right = new SpyExpression(3);

        $expr = Comparison::of(3)->equal($right)->build();

        self::assertTrue($expr());
        self::assertSame(1, $right->invocations);
    }
}
