<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcDivFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\StringLiteral;
use PHPUnit\Framework\TestCase;
use DivisionByZeroError;

final class BcDivFunctionTest extends TestCase
{
    public function testDividesToTheGivenScale(): void
    {
        $function = new BcDivFunction(new StringLiteral('10'), new StringLiteral('4'), 2);

        self::assertSame(2.5, $function());
        self::assertSame('2.50', (string) $function->bcValue());
    }

    public function testThrowsTheNativeErrorWhenDividingByZero(): void
    {
        $function = new BcDivFunction(new StringLiteral('1'), new StringLiteral('0'));

        $this->expectException(DivisionByZeroError::class);

        $function();
    }
}
