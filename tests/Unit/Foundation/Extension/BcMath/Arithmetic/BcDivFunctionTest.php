<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcDivFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PHPUnit\Framework\TestCase;
use DivisionByZeroError;

final class BcDivFunctionTest extends TestCase
{
    public function testDividesToTheGivenScale(): void
    {
        $function = new BcDivFunction(new IntLiteral(10), new IntLiteral(4), new IntLiteral(2));

        self::assertSame(2.5, $function());
        self::assertSame('2.50', (string) $function->bcValue());
    }

    public function testThrowsTheNativeErrorWhenDividingByZero(): void
    {
        $function = new BcDivFunction(new IntLiteral(1), new IntLiteral(0));

        $this->expectException(DivisionByZeroError::class);

        $function();
    }
}
