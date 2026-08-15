<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Conditional;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\IncompleteIfBuilderException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\NoMatchedCaseException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;
use PhpArchitecture\LazyOperators\Tests\Support\RecordingExpression;
use PHPUnit\Framework\TestCase;

final class ConditionalTest extends TestCase
{
    public function testIfBuildsAnIfElseOperator(): void
    {
        $expr = Conditional::if(true)->then('yes')->else('no')->build();

        self::assertInstanceOf(IfElseOperator::class, $expr);
    }

    public function testIfEvaluatesTrueBranch(): void
    {
        $expr = Conditional::if(true)->then('yes')->else('no')->build();

        self::assertSame('yes', $expr());
    }

    public function testIfEvaluatesFalseBranch(): void
    {
        $expr = Conditional::if(false)->then('yes')->else('no')->build();

        self::assertSame('no', $expr());
    }

    public function testIfBuildThrowsWhenThenIsMissing(): void
    {
        $this->expectException(IncompleteIfBuilderException::class);

        Conditional::if(true)->else('no')->build();
    }

    public function testIfBuildThrowsWhenElseIsMissing(): void
    {
        $this->expectException(IncompleteIfBuilderException::class);

        Conditional::if(true)->then('yes')->build();
    }

    public function testSwitchBuildsASwitchCaseOperator(): void
    {
        $expr = Conditional::switch(1)->case(1, 'a')->build();

        self::assertInstanceOf(SwitchCaseOperator::class, $expr);
    }

    public function testSwitchMatchesFirstMatchingCase(): void
    {
        $expr = Conditional::switch(2)
            ->case(1, 'a')
            ->case(2, 'b')
            ->build();

        self::assertSame('b', $expr());
    }

    public function testSwitchFallsBackToDefault(): void
    {
        $expr = Conditional::switch(99)
            ->case(1, 'a')
            ->default('fallback')
            ->build();

        self::assertSame('fallback', $expr());
    }

    public function testSwitchThrowsWhenNoCaseMatchesAndNoDefault(): void
    {
        $expr = Conditional::switch(99)
            ->case(1, 'a')
            ->build();

        $this->expectException(NoMatchedCaseException::class);

        $expr();
    }

    /**
     * IfElseOperator/SwitchCaseOperator only ever implement the generic Expression contract —
     * their branches may differ in type, so build() can't guarantee anything narrower, whether or
     * not a Decorator is configured. Feeding a numeric/boolean-branched if()/switch() result into
     * Arithmetic/Logical always needs an explicit Cast; that's a deliberate design choice (see
     * DecoratesNodes::decorate()'s docblock), not a gap decoration introduces.
     */
    public function testIfWithTypedBranchesStillStaysAGenericExpression(): void
    {
        $rate = Conditional::if(true)->then(0.9)->else(1.0)->build();

        self::assertInstanceOf(IfElseOperator::class, $rate);
        self::assertNotInstanceOf(NumberValue::class, $rate);
        self::assertNotInstanceOf(BooleanValue::class, $rate);
        self::assertNotInstanceOf(StringValue::class, $rate);
    }

    public function testIfWithADecoratorAlsoStaysAGenericExpressionJustLikeUndecorated(): void
    {
        RecordingExpression::reset();
        $config = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new RecordingExpression($node),
        );

        $rate = Conditional::if(true, $config)->then(0.9)->else(1.0)->build();

        self::assertNotInstanceOf(NumberValue::class, $rate);
        self::assertSame(0.9, $rate());
    }

    public function testSwitchWithTypedCasesStillStaysAGenericExpression(): void
    {
        $tier = Conditional::switch(2)->case(1, 10.0)->case(2, 20.0)->default(0.0)->build();

        self::assertInstanceOf(SwitchCaseOperator::class, $tier);
        self::assertNotInstanceOf(NumberValue::class, $tier);
    }

    public function testEmptySwitchStillBuildsAPlainSwitchCaseOperator(): void
    {
        $expr = Conditional::switch(1)->build();

        self::assertInstanceOf(SwitchCaseOperator::class, $expr);
        self::assertSame(SwitchCaseOperator::class, $expr::class);
    }
}
