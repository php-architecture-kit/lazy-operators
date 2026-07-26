<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Conditional\Conditional;
use PhpArchitecture\LazyOperators\Foundation\Conditional\Exception\IncompleteIfBuilderException;
use PhpArchitecture\LazyOperators\Foundation\Conditional\Exception\NoMatchedCaseException;
use PhpArchitecture\LazyOperators\Foundation\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\SwitchCaseOperator;
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
}
