<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Arithmetic;

use DivisionByZeroError;
use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\ModuloOperator;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class ModuloOperatorTest extends TestCase
{
    public function testComputesRemainder(): void
    {
        $operator = new ModuloOperator(new NumericSpyExpression(10), new NumericSpyExpression(3));

        self::assertSame(1, $operator());
    }

    public function testModuloByZeroThrows(): void
    {
        $operator = new ModuloOperator(new NumericSpyExpression(10), new NumericSpyExpression(0));

        $this->expectException(DivisionByZeroError::class);

        $operator();
    }
}
