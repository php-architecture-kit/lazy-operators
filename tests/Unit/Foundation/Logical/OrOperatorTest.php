<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Logical\OrOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class OrOperatorTest extends TestCase
{
    public function testFalseOrFalseReturnsFalse(): void
    {
        $operator = new OrOperator(new SpyExpression(false), new SpyExpression(false));

        self::assertFalse($operator());
    }

    public function testFalseOrTrueReturnsTrue(): void
    {
        $operator = new OrOperator(new SpyExpression(false), new SpyExpression(true));

        self::assertTrue($operator());
    }

    public function testShortCircuitsWhenLeftIsTrue(): void
    {
        $right = new SpyExpression(false);
        $operator = new OrOperator(new SpyExpression(true), $right);

        self::assertTrue($operator());
        self::assertSame(0, $right->invocations);
    }
}
