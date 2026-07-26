<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Arithmetic;

use DivisionByZeroError;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ModuloOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class ModuloOperatorTest extends TestCase
{
    public function testComputesRemainder(): void
    {
        $operator = new ModuloOperator(new SpyExpression(10), new SpyExpression(3));

        self::assertSame(1, $operator());
    }

    public function testModuloByZeroThrows(): void
    {
        $operator = new ModuloOperator(new SpyExpression(10), new SpyExpression(0));

        $this->expectException(DivisionByZeroError::class);

        $operator();
    }
}
