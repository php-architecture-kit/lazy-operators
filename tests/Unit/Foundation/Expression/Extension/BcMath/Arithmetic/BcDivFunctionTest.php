<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Arithmetic\BcDivFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PHPUnit\Framework\TestCase;
use DivisionByZeroError;

final class BcDivFunctionTest extends TestCase
{
    public function testDividesToTheGivenScale(): void
    {
        $function = new BcDivFunction(new IntLiteral(10), new IntLiteral(4), new IntLiteral(2));

        self::assertSame(2.5, $function());
        self::assertSame('2.5', (string) $function->__invoke());
    }

    public function testThrowsTheNativeErrorWhenDividingByZero(): void
    {
        $function = new BcDivFunction(new IntLiteral(1), new IntLiteral(0));

        $this->expectException(DivisionByZeroError::class);

        $function();
    }
}
