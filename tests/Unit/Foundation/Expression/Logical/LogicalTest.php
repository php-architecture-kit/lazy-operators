<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\AndOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\Logical;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\NotOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\OrOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\XorOperator;
use PhpArchitecture\LazyOperators\Tests\Support\BooleanSpyExpression;
use PHPUnit\Framework\TestCase;

final class LogicalTest extends TestCase
{
    public function testOfWrapsARawValue(): void
    {
        self::assertTrue(Logical::of(true)->build()());
    }

    public function testOfAcceptsAnExpressionDirectly(): void
    {
        self::assertTrue(Logical::of(new BooleanSpyExpression(true))->build()());
    }

    public function testBuildReturnsAnExpressionNotLogical(): void
    {
        $built = Logical::of(true)->and(true)->build();

        self::assertInstanceOf(Expression::class, $built);
        self::assertNotInstanceOf(Logical::class, $built);
    }

    public function testAndProducesAnAndOperator(): void
    {
        self::assertInstanceOf(AndOperator::class, Logical::of(true)->and(true)->build());
    }

    public function testOrProducesAnOrOperator(): void
    {
        self::assertInstanceOf(OrOperator::class, Logical::of(true)->or(false)->build());
    }

    public function testXorProducesAnXorOperator(): void
    {
        self::assertInstanceOf(XorOperator::class, Logical::of(true)->xor(false)->build());
    }

    public function testNotProducesANotOperator(): void
    {
        self::assertInstanceOf(NotOperator::class, Logical::of(true)->not()->build());
    }

    public function testLinearChainEvaluatesCorrectly(): void
    {
        $expr = Logical::of(true)->and(true)->build();

        self::assertTrue($expr());
    }

    public function testNotNegatesTheCurrentValue(): void
    {
        $expr = Logical::of(true)->not()->build();

        self::assertFalse($expr());
    }

    public function testForkingCombinesTwoIndependentBranchesIntoOneTree(): void
    {
        $left = Logical::of(true)->and(false)->build();  // false
        $right = Logical::of(false)->or(true)->build();  // true

        $expr = Logical::of($left)->xor($right)->build();

        self::assertTrue($expr()); // false xor true
    }

    public function testOperandsAcceptExpressionInstancesDirectly(): void
    {
        $right = new BooleanSpyExpression(false);

        $expr = Logical::of(true)->and($right)->build();

        self::assertFalse($expr());
        self::assertSame(1, $right->invocations);
    }
}
