<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\AdditionOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\DivisionOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ExponentiationOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ModuloOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\MultiplicationOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\SubtractionOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class ArithmeticTest extends TestCase
{
    public function testOfWrapsARawValue(): void
    {
        self::assertSame(2, Arithmetic::of(2)->build()());
    }

    public function testOfAcceptsAnExpressionDirectly(): void
    {
        self::assertSame(2, Arithmetic::of(new SpyExpression(2))->build()());
    }

    public function testBuildReturnsAnExpressionNotArithmetic(): void
    {
        $built = Arithmetic::of(1)->add(1)->build();

        self::assertInstanceOf(Expression::class, $built);
        self::assertNotInstanceOf(Arithmetic::class, $built);
    }

    public function testAddProducesAnAdditionOperator(): void
    {
        self::assertInstanceOf(AdditionOperator::class, Arithmetic::of(2)->add(3)->build());
    }

    public function testSubtractProducesASubtractionOperator(): void
    {
        self::assertInstanceOf(SubtractionOperator::class, Arithmetic::of(2)->subtract(3)->build());
    }

    public function testMultiplyProducesAMultiplicationOperator(): void
    {
        self::assertInstanceOf(MultiplicationOperator::class, Arithmetic::of(2)->multiply(3)->build());
    }

    public function testDivideProducesADivisionOperator(): void
    {
        self::assertInstanceOf(DivisionOperator::class, Arithmetic::of(6)->divide(3)->build());
    }

    public function testModuloProducesAModuloOperator(): void
    {
        self::assertInstanceOf(ModuloOperator::class, Arithmetic::of(5)->modulo(2)->build());
    }

    public function testPowerProducesAnExponentiationOperator(): void
    {
        self::assertInstanceOf(ExponentiationOperator::class, Arithmetic::of(2)->power(3)->build());
    }

    public function testLinearChainEvaluatesLeftToRight(): void
    {
        $expr = Arithmetic::of(2)
            ->multiply(3)
            ->add(4)
            ->build();

        self::assertSame(10, $expr()); // (2*3)+4
    }

    public function testForkingCombinesTwoIndependentBranchesIntoOneTree(): void
    {
        $left = Arithmetic::of(2)->add(3);
        $right = Arithmetic::of(10)->subtract(4)->build();

        $expr = $left->multiply($right)->build();

        self::assertSame(30, $expr()); // (2+3)*(10-4)
    }

    public function testForkingDoesNotMutateTheOriginalBranches(): void
    {
        $base = Arithmetic::of(2)->add(3);

        $timesFour = $base->multiply(4)->build();
        $timesTen = $base->multiply(10)->build();

        self::assertSame(20, $timesFour()); // (2+3)*4
        self::assertSame(50, $timesTen());  // (2+3)*10 -- $base unaffected by the first fork
    }

    public function testOperandsAreAutoWrappedInValue(): void
    {
        $expr = Arithmetic::of(2)->add(3)->build();

        self::assertSame(5, $expr());
    }

    public function testOperandsAcceptExpressionInstancesDirectly(): void
    {
        $right = new SpyExpression(3);

        $expr = Arithmetic::of(2)->add($right)->build();

        self::assertSame(5, $expr());
        self::assertSame(1, $right->invocations);
    }
}
