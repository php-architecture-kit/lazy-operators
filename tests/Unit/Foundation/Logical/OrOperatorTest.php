<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Logical\OrOperator;
use PhpArchitecture\LazyOperators\Tests\Support\BooleanSpyExpression;
use PHPUnit\Framework\TestCase;

final class OrOperatorTest extends TestCase
{
    public function testFalseOrFalseReturnsFalse(): void
    {
        $operator = new OrOperator(new BooleanSpyExpression(false), new BooleanSpyExpression(false));

        self::assertFalse($operator());
    }

    public function testFalseOrTrueReturnsTrue(): void
    {
        $operator = new OrOperator(new BooleanSpyExpression(false), new BooleanSpyExpression(true));

        self::assertTrue($operator());
    }

    public function testShortCircuitsWhenLeftIsTrue(): void
    {
        $right = new BooleanSpyExpression(false);
        $operator = new OrOperator(new BooleanSpyExpression(true), $right);

        self::assertTrue($operator());
        self::assertSame(0, $right->invocations);
    }
}
