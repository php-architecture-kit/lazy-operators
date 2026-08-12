<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparator\Comparator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparator\SpaceshipOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Tests\Support\RecordingExpression;
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

    public function testSpaceshipResultIsUsableDirectlyByArithmeticWithoutACast(): void
    {
        $spaceship = Comparator::of(5)->spaceship(3)->build();

        self::assertInstanceOf(IntegerValue::class, $spaceship);
        self::assertSame(10, Arithmetic::of($spaceship)->multiply(10)->build()());
    }

    public function testSpaceshipResultWithADecoratorIsStillUsableDirectlyByArithmetic(): void
    {
        // Regression guard: a plain decorate() call only re-instantiates the configured
        // Decorator, which alone can't guarantee IntegerValue. DecoratesNodes::decorate() must
        // re-expose the result as IntegerValue itself (because SpaceshipOperator, the node being
        // decorated, already was one) — otherwise a decorated Comparator result would stop being
        // usable by Arithmetic without a Cast, even though an undecorated one never was.
        RecordingExpression::reset();
        $config = new ExpressionTreeConfig(new RecordingExpression(new IntLiteral(0)));

        $spaceship = Comparator::of(5, $config)->spaceship(3)->build();

        self::assertSame(10, Arithmetic::of($spaceship)->multiply(10)->build()());
    }
}
