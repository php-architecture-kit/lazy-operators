<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Conditional;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\IncompleteIfBuilderException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\NoMatchedCaseException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\Logical;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
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

    public function testIfWithNumericBranchesIsUsableDirectlyByArithmeticWithoutACast(): void
    {
        $rate = Conditional::if(true)->then(0.9)->else(1.0)->build();

        self::assertInstanceOf(IfElseOperator::class, $rate);
        self::assertInstanceOf(NumberValue::class, $rate);
        self::assertSame(90.0, Arithmetic::of(100)->multiply($rate)->build()());
    }

    public function testIfWithBooleanBranchesIsUsableDirectlyByLogicalWithoutACast(): void
    {
        $flag = Conditional::if(true)->then(true)->else(false)->build();

        self::assertInstanceOf(IfElseOperator::class, $flag);
        self::assertInstanceOf(BooleanValue::class, $flag);
        self::assertTrue(Logical::of($flag)->and(true)->build()());
    }

    public function testIfWithMixedTypeBranchesStaysAGenericExpression(): void
    {
        $mixed = Conditional::if(true)->then(1)->else('fallback')->build();

        self::assertInstanceOf(IfElseOperator::class, $mixed);
        self::assertNotInstanceOf(NumberValue::class, $mixed);
        self::assertNotInstanceOf(BooleanValue::class, $mixed);
    }

    public function testIfWithStringBranchesIsAlsoNarrowedAndStaysAnIfElseOperator(): void
    {
        // Regression guard: string branches are the example used by testIfBuildsAnIfElseOperator()
        // above — narrowing to StringIfElseOperator must not break `instanceof IfElseOperator`.
        $expr = Conditional::if(true)->then('yes')->else('no')->build();

        self::assertInstanceOf(IfElseOperator::class, $expr);
        self::assertInstanceOf(StringValue::class, $expr);
    }

    public function testIfWithADecoratorFallsBackToGenericExpressionLikeArithmeticDoes(): void
    {
        RecordingExpression::reset();
        $config = new ExpressionTreeConfig(new RecordingExpression(new IntLiteral(0)));

        $rate = Conditional::if(true, $config)->then(0.9)->else(1.0)->build();

        self::assertNotInstanceOf(NumberValue::class, $rate);
        self::assertSame(0.9, $rate());
    }

    public function testSwitchWithNumericCasesIsUsableDirectlyByArithmeticWithoutACast(): void
    {
        $tier = Conditional::switch(2)->case(1, 10.0)->case(2, 20.0)->default(0.0)->build();

        self::assertInstanceOf(SwitchCaseOperator::class, $tier);
        self::assertInstanceOf(NumberValue::class, $tier);
        self::assertSame(25.0, Arithmetic::of($tier)->add(5)->build()());
    }

    public function testSwitchWithMixedTypeCasesStaysAGenericExpression(): void
    {
        $mixed = Conditional::switch(1)->case(1, 10)->case(2, 'twenty')->build();

        self::assertInstanceOf(SwitchCaseOperator::class, $mixed);
        self::assertNotInstanceOf(NumberValue::class, $mixed);
    }

    public function testEmptySwitchStillBuildsAPlainSwitchCaseOperator(): void
    {
        $expr = Conditional::switch(1)->build();

        self::assertInstanceOf(SwitchCaseOperator::class, $expr);
        self::assertSame(SwitchCaseOperator::class, $expr::class);
    }
}
