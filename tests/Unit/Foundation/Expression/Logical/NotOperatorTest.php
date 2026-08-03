<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\NotOperator;
use PhpArchitecture\LazyOperators\Tests\Support\BooleanSpyExpression;
use PHPUnit\Framework\TestCase;

final class NotOperatorTest extends TestCase
{
    public function testNegatesTrueToFalse(): void
    {
        $operator = new NotOperator(new BooleanSpyExpression(true));

        self::assertFalse($operator());
    }

    public function testNegatesFalseToTrue(): void
    {
        $operator = new NotOperator(new BooleanSpyExpression(false));

        self::assertTrue($operator());
    }
}
