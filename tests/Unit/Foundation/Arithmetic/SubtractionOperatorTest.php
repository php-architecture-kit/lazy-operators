<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\SubtractionOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class SubtractionOperatorTest extends TestCase
{
    public function testProducesPositiveResult(): void
    {
        $operator = new SubtractionOperator(new SpyExpression(5), new SpyExpression(3));

        self::assertSame(2, $operator());
    }

    public function testProducesNegativeResult(): void
    {
        $operator = new SubtractionOperator(new SpyExpression(3), new SpyExpression(5));

        self::assertSame(-2, $operator());
    }
}
