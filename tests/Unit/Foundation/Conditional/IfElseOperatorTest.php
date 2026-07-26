<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class IfElseOperatorTest extends TestCase
{
    public function testTrueConditionInvokesThenOnly(): void
    {
        $then = new SpyExpression('then');
        $else = new SpyExpression('else');
        $operator = new IfElseOperator(new SpyExpression(true), $then, $else);

        self::assertSame('then', $operator());
        self::assertSame(1, $then->invocations);
        self::assertSame(0, $else->invocations);
    }

    public function testFalseConditionInvokesElseOnly(): void
    {
        $then = new SpyExpression('then');
        $else = new SpyExpression('else');
        $operator = new IfElseOperator(new SpyExpression(false), $then, $else);

        self::assertSame('else', $operator());
        self::assertSame(0, $then->invocations);
        self::assertSame(1, $else->invocations);
    }
}
