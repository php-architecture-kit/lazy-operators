<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Comparison\EqualOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class EqualOperatorTest extends TestCase
{
    public function testLooselyEqualValuesReturnTrue(): void
    {
        $operator = new EqualOperator(new SpyExpression('1'), new SpyExpression(1));

        self::assertTrue($operator());
    }

    public function testDifferentValuesReturnFalse(): void
    {
        $operator = new EqualOperator(new SpyExpression(1), new SpyExpression(2));

        self::assertFalse($operator());
    }
}
