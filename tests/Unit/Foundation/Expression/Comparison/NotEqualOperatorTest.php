<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\NotEqualOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class NotEqualOperatorTest extends TestCase
{
    public function testDifferentValuesReturnTrue(): void
    {
        $operator = new NotEqualOperator(new SpyExpression(1), new SpyExpression(2));

        self::assertTrue($operator());
    }

    public function testLooselyEqualValuesReturnFalse(): void
    {
        $operator = new NotEqualOperator(new SpyExpression('1'), new SpyExpression(1));

        self::assertFalse($operator());
    }
}
