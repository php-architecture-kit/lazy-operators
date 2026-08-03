<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PHPUnit\Framework\TestCase;

final class BcAddFunctionTest extends TestCase
{
    public function testAddsTwoDecimalStringsToTheGivenScale(): void
    {
        $function = new BcAddFunction(new FloatLiteral(1.1), new FloatLiteral(2.2), new IntLiteral(2));

        self::assertSame(3.3, $function());
        self::assertSame(3.3, $function->__invoke());
    }

    public function test(): void
    {
        $function = new BcAddFunction(new FloatLiteral(0.1), new FloatLiteral(0.2));

        self::assertSame(0.3, $function());
    }

    public function testDefaultsToTheNativeScaleWhenNoneGiven(): void
    {
        $function = new BcAddFunction(new IntLiteral(1), new IntLiteral(2));

        self::assertSame(3, $function());
    }

    public function testChainsWithAnotherBcMathNodeWithoutRoundTrippingThroughAPrimitive(): void
    {
        $inner = new BcAddFunction(new FloatLiteral(0.1), new FloatLiteral(0.2), new IntLiteral(10));
        $outer = new BcAddFunction($inner, new FloatLiteral(0.3), new IntLiteral(10));

        self::assertSame(0.6, $outer->__invoke());
    }
}
