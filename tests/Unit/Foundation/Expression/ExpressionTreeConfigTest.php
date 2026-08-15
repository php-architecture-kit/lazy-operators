<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation;

use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Conditional;
use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\RecordingExpression;
use PHPUnit\Framework\TestCase;

final class ExpressionTreeConfigTest extends TestCase
{
    protected function setUp(): void
    {
        RecordingExpression::reset();
    }

    public function testDecoratorDefaultsToNull(): void
    {
        self::assertNull((new ExpressionTreeConfig())->decorator);
    }

    public function testDecoratorHoldsTheGivenPrototype(): void
    {
        $decorator = new RecordingExpression(new IntLiteral(0));

        self::assertSame($decorator, (new ExpressionTreeConfig($decorator))->decorator);
    }

    public function testDecoratorFactoryDefaultsToNull(): void
    {
        self::assertNull((new ExpressionTreeConfig())->decoratorFactory);
    }

    public function testPrototypeIsNormalisedIntoAFactoryOverItsOwnClass(): void
    {
        $config = new ExpressionTreeConfig(new RecordingExpression(new IntLiteral(0)));

        self::assertNotNull($config->decoratorFactory);

        $inner = new IntLiteral(7);
        $decorated = ($config->decoratorFactory)($inner);

        self::assertInstanceOf(RecordingExpression::class, $decorated);
        self::assertSame($inner, $decorated->unwrap());
    }

    public function testDecoratedByStoresTheFactoryAndLeavesThePrototypePropertyNull(): void
    {
        $factory = static fn (Expression $node): Decorator => new RecordingExpression($node);

        $config = ExpressionTreeConfig::decoratedBy($factory);

        self::assertSame($factory, $config->decoratorFactory);
        self::assertNull($config->decorator);
    }

    public function testDecoratedByWithNullDisablesDecoration(): void
    {
        $config = ExpressionTreeConfig::decoratedBy(null);

        self::assertNull($config->decoratorFactory);
        self::assertSame(5, Arithmetic::of(2, $config)->add(3)->build()());
        self::assertSame([], RecordingExpression::$log);
    }

    public function testDecoratedNodeUnwrapsBackToTheOriginalInner(): void
    {
        $inner = new IntLiteral(5);

        self::assertSame($inner, (new RecordingExpression($inner))->unwrap());
    }

    public function testArithmeticDecoratesEveryNodeInEvaluationOrder(): void
    {
        $config = new ExpressionTreeConfig(new RecordingExpression(new IntLiteral(0)));

        $expr = Arithmetic::of(2, $config)->add(3)->multiply(10)->build();

        self::assertSame(50, $expr());
        self::assertSame([2, 3, 5, 10, 50], RecordingExpression::$log);
    }

    public function testIfDecoratesConditionTakenBranchAndTopNode(): void
    {
        $config = new ExpressionTreeConfig(new RecordingExpression(new IntLiteral(0)));

        $expr = Conditional::if(true, $config)->then('yes')->else('no')->build();

        self::assertSame('yes', $expr());
        self::assertSame([true, 'yes', 'yes'], RecordingExpression::$log);
    }

    public function testSwitchDecoratesSubjectEvaluatedCasesMatchedValueAndTopNode(): void
    {
        $config = new ExpressionTreeConfig(new RecordingExpression(new IntLiteral(0)));

        $expr = Conditional::switch(2, $config)
            ->case(1, 'a')
            ->case(2, 'b')
            ->default('fallback')
            ->build();

        self::assertSame('b', $expr());
        self::assertSame([2, 1, 2, 'b', 'b'], RecordingExpression::$log);
    }
}
