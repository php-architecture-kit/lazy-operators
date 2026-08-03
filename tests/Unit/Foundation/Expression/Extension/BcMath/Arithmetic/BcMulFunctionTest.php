<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Arithmetic\BcMulFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PHPUnit\Framework\TestCase;

final class BcMulFunctionTest extends TestCase
{
    public function testMultipliesToTheGivenScale(): void
    {
        $function = new BcMulFunction(new FloatLiteral(2.5), new IntLiteral(4), new IntLiteral(2));

        self::assertSame(10.0, $function());
        self::assertSame('10', (string) $function->__invoke());
    }

    public function testDefaultsToTheNativeScaleWhenNoneGiven(): void
    {
        $function = new BcMulFunction(new IntLiteral(3), new IntLiteral(4));

        self::assertSame(12, $function());
    }
}
