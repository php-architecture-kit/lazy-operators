<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Support;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\BcNumberLiteral;
use PHPUnit\Framework\TestCase;

final class BcNumberLiteralTest extends TestCase
{
    public function testBcValueReturnsTheWrappedNumberUnchanged(): void
    {
        $number = new Number('1.50');
        $literal = new BcNumberLiteral($number);

        self::assertSame($number, $literal->bcValue());
    }

    public function testInvokeCastsDownToAPrimitive(): void
    {
        $literal = new BcNumberLiteral(new Number('3.30'));

        self::assertSame(3.3, $literal());
    }

    public function testInvokeReturnsAnIntegerWhenTheNumberHasNoScale(): void
    {
        $literal = new BcNumberLiteral(new Number('5'));

        self::assertSame(5, $literal());
    }
}
