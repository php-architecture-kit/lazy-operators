<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Comparison\LessThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class LessThanOrEqualOperatorTest extends TestCase
{
    public function testEqualValuesReturnTrue(): void
    {
        $operator = new LessThanOrEqualOperator(new SpyExpression(2), new SpyExpression(2));

        self::assertTrue($operator());
    }

    public function testSmallerValueReturnsTrue(): void
    {
        $operator = new LessThanOrEqualOperator(new SpyExpression(2), new SpyExpression(3));

        self::assertTrue($operator());
    }

    public function testGreaterValueReturnsFalse(): void
    {
        $operator = new LessThanOrEqualOperator(new SpyExpression(3), new SpyExpression(2));

        self::assertFalse($operator());
    }
}
