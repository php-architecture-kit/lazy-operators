<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\NotIdenticalOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class NotIdenticalOperatorTest extends TestCase
{
    public function testDifferentTypesReturnTrue(): void
    {
        $operator = new NotIdenticalOperator(new SpyExpression('1'), new SpyExpression(1));

        self::assertTrue($operator());
    }

    public function testSameTypeAndValueReturnFalse(): void
    {
        $operator = new NotIdenticalOperator(new SpyExpression(1), new SpyExpression(1));

        self::assertFalse($operator());
    }
}
