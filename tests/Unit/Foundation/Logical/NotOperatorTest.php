<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Logical\NotOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class NotOperatorTest extends TestCase
{
    public function testNegatesTrueToFalse(): void
    {
        $operator = new NotOperator(new SpyExpression(true));

        self::assertFalse($operator());
    }

    public function testNegatesFalseToTrue(): void
    {
        $operator = new NotOperator(new SpyExpression(false));

        self::assertTrue($operator());
    }
}
