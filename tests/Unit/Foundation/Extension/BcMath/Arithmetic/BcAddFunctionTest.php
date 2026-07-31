<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Arithmetic;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\StringLiteral;
use PHPUnit\Framework\TestCase;

final class BcAddFunctionTest extends TestCase
{
    public function testAddsTwoDecimalStringsToTheGivenScale(): void
    {
        $function = new BcAddFunction(new StringLiteral('1.1'), new StringLiteral('2.2'), 2);

        self::assertSame(3.3, $function());
        self::assertSame('3.30', (string) $function->bcValue());
    }

    public function testDefaultsToTheNativeScaleWhenNoneGiven(): void
    {
        $function = new BcAddFunction(new StringLiteral('1'), new StringLiteral('2'));

        self::assertSame(3, $function());
    }

    public function testAcceptsANativeBcMathNumberDirectly(): void
    {
        $function = new BcAddFunction(new Number('1.50'), new StringLiteral('2.50'), 2);

        self::assertSame(4.0, $function());
    }

    public function testChainsWithAnotherBcMathNodeWithoutRoundTrippingThroughAPrimitive(): void
    {
        $inner = new BcAddFunction(new StringLiteral('0.1'), new StringLiteral('0.2'), 10);
        $outer = new BcAddFunction($inner, new StringLiteral('0.3'), 10);

        self::assertSame('0.6000000000', (string) $outer->bcValue());
    }
}
