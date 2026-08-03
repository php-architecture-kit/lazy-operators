<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\IdenticalOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class IdenticalOperatorTest extends TestCase
{
    public function testSameTypeAndValueReturnsTrue(): void
    {
        $operator = new IdenticalOperator(new SpyExpression(1), new SpyExpression(1));

        self::assertTrue($operator());
    }

    public function testDifferentTypesReturnFalse(): void
    {
        $operator = new IdenticalOperator(new SpyExpression('1'), new SpyExpression(1));

        self::assertFalse($operator());
    }
}
