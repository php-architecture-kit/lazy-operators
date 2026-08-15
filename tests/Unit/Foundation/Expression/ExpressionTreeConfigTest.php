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

    public function testDecoratorFactoryDefaultsToNull(): void
    {
        self::assertNull((new ExpressionTreeConfig())->decoratorFactory);
    }

    public function testTheFactoryWrapsTheNodeItIsHanded(): void
    {
        $config = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new RecordingExpression($node),
        );

        self::assertNotNull($config->decoratorFactory);

        $decorated = ($config->decoratorFactory)(new IntLiteral(7));

        self::assertInstanceOf(RecordingExpression::class, $decorated);
        self::assertSame(7, $decorated());
        self::assertSame([7], RecordingExpression::$log);
    }

    public function testDecoratedByStoresTheFactory(): void
    {
        $factory = static fn (Expression $node): Decorator => new RecordingExpression($node);

        self::assertSame($factory, ExpressionTreeConfig::decoratedBy($factory)->decoratorFactory);
        self::assertSame($factory, (new ExpressionTreeConfig($factory))->decoratorFactory);
    }

    public function testDecoratedByWithNullDisablesDecoration(): void
    {
        $config = ExpressionTreeConfig::decoratedBy(null);

        self::assertNull($config->decoratorFactory);
        self::assertSame(5, Arithmetic::of(2, $config)->add(3)->build()());
        self::assertSame([], RecordingExpression::$log);
    }

    public function testArithmeticDecoratesEveryNodeInEvaluationOrder(): void
    {
        $config = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new RecordingExpression($node),
        );

        $expr = Arithmetic::of(2, $config)->add(3)->multiply(10)->build();

        self::assertSame(50, $expr());
        self::assertSame([2, 3, 5, 10, 50], RecordingExpression::$log);
    }

    public function testIfDecoratesConditionTakenBranchAndTopNode(): void
    {
        $config = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new RecordingExpression($node),
        );

        $expr = Conditional::if(true, $config)->then('yes')->else('no')->build();

        self::assertSame('yes', $expr());
        self::assertSame([true, 'yes', 'yes'], RecordingExpression::$log);
    }

    public function testSwitchDecoratesSubjectEvaluatedCasesMatchedValueAndTopNode(): void
    {
        $config = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new RecordingExpression($node),
        );

        $expr = Conditional::switch(2, $config)
            ->case(1, 'a')
            ->case(2, 'b')
            ->default('fallback')
            ->build();

        self::assertSame('b', $expr());
        self::assertSame([2, 1, 2, 'b', 'b'], RecordingExpression::$log);
    }
}
