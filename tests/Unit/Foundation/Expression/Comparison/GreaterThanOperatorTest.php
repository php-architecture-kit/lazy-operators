<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\GreaterThanOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class GreaterThanOperatorTest extends TestCase
{
    public function testGreaterValueReturnsTrue(): void
    {
        $operator = new GreaterThanOperator(new SpyExpression(3), new SpyExpression(2));

        self::assertTrue($operator());
    }

    public function testSmallerValueReturnsFalse(): void
    {
        $operator = new GreaterThanOperator(new SpyExpression(2), new SpyExpression(3));

        self::assertFalse($operator());
    }

    public function testEqualValuesReturnFalse(): void
    {
        $operator = new GreaterThanOperator(new SpyExpression(2), new SpyExpression(2));

        self::assertFalse($operator());
    }
}
