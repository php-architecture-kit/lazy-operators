<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Comparison\LessThanOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class LessThanOperatorTest extends TestCase
{
    public function testSmallerValueReturnsTrue(): void
    {
        $operator = new LessThanOperator(new SpyExpression(2), new SpyExpression(3));

        self::assertTrue($operator());
    }

    public function testGreaterValueReturnsFalse(): void
    {
        $operator = new LessThanOperator(new SpyExpression(3), new SpyExpression(2));

        self::assertFalse($operator());
    }

    public function testEqualValuesReturnFalse(): void
    {
        $operator = new LessThanOperator(new SpyExpression(2), new SpyExpression(2));

        self::assertFalse($operator());
    }
}
