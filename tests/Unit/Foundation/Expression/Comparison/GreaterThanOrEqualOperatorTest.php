<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\GreaterThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class GreaterThanOrEqualOperatorTest extends TestCase
{
    public function testEqualValuesReturnTrue(): void
    {
        $operator = new GreaterThanOrEqualOperator(new SpyExpression(2), new SpyExpression(2));

        self::assertTrue($operator());
    }

    public function testGreaterValueReturnsTrue(): void
    {
        $operator = new GreaterThanOrEqualOperator(new SpyExpression(3), new SpyExpression(2));

        self::assertTrue($operator());
    }

    public function testSmallerValueReturnsFalse(): void
    {
        $operator = new GreaterThanOrEqualOperator(new SpyExpression(2), new SpyExpression(3));

        self::assertFalse($operator());
    }
}
