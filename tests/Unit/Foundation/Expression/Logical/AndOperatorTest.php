<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\AndOperator;
use PhpArchitecture\LazyOperators\Tests\Support\BooleanSpyExpression;
use PHPUnit\Framework\TestCase;

final class AndOperatorTest extends TestCase
{
    public function testTrueAndTrueReturnsTrue(): void
    {
        $operator = new AndOperator(new BooleanSpyExpression(true), new BooleanSpyExpression(true));

        self::assertTrue($operator());
    }

    public function testTrueAndFalseReturnsFalse(): void
    {
        $operator = new AndOperator(new BooleanSpyExpression(true), new BooleanSpyExpression(false));

        self::assertFalse($operator());
    }

    public function testShortCircuitsWhenLeftIsFalse(): void
    {
        $right = new BooleanSpyExpression(true);
        $operator = new AndOperator(new BooleanSpyExpression(false), $right);

        self::assertFalse($operator());
        self::assertSame(0, $right->invocations);
    }
}
