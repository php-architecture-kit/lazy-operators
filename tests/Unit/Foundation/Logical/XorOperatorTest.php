<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Logical\XorOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class XorOperatorTest extends TestCase
{
    public function testTrueXorFalseReturnsTrue(): void
    {
        $operator = new XorOperator(new SpyExpression(true), new SpyExpression(false));

        self::assertTrue($operator());
    }

    public function testTrueXorTrueReturnsFalse(): void
    {
        $operator = new XorOperator(new SpyExpression(true), new SpyExpression(true));

        self::assertFalse($operator());
    }

    public function testBothOperandsAreAlwaysInvoked(): void
    {
        $left = new SpyExpression(true);
        $right = new SpyExpression(true);
        $operator = new XorOperator($left, $right);

        $operator();

        self::assertSame(1, $left->invocations);
        self::assertSame(1, $right->invocations);
    }
}
