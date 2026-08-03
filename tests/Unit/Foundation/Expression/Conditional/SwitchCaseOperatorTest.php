<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\CaseOfSwitchCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\NoMatchedCaseException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class SwitchCaseOperatorTest extends TestCase
{
    public function testReturnsValueOfFirstMatchingCase(): void
    {
        $matchedValue = new SpyExpression('b');
        $unmatchedValue = new SpyExpression('a');

        $operator = new SwitchCaseOperator(
            new SpyExpression(2),
            [
                new CaseOfSwitchCase(new SpyExpression(1), $unmatchedValue),
                new CaseOfSwitchCase(new SpyExpression(2), $matchedValue),
            ],
        );

        self::assertSame('b', $operator());
        self::assertSame(0, $unmatchedValue->invocations);
        self::assertSame(1, $matchedValue->invocations);
    }

    public function testMatchesStrictly(): void
    {
        $default = new SpyExpression('default');

        $operator = new SwitchCaseOperator(
            new SpyExpression('1'),
            [
                new CaseOfSwitchCase(new SpyExpression(1), new SpyExpression('matched')),
            ],
            $default,
        );

        self::assertSame('default', $operator());
    }

    public function testFallsBackToDefaultWhenNoCaseMatches(): void
    {
        $operator = new SwitchCaseOperator(
            new SpyExpression(99),
            [
                new CaseOfSwitchCase(new SpyExpression(1), new SpyExpression('a')),
            ],
            new SpyExpression('default'),
        );

        self::assertSame('default', $operator());
    }

    public function testThrowsWhenNoCaseMatchesAndNoDefault(): void
    {
        $operator = new SwitchCaseOperator(
            new SpyExpression(99),
            [
                new CaseOfSwitchCase(new SpyExpression(1), new SpyExpression('a')),
            ],
        );

        $this->expectException(NoMatchedCaseException::class);

        $operator();
    }
}
