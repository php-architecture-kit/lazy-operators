<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Comparison\BcCompFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PHPUnit\Framework\TestCase;

final class BcCompFunctionTest extends TestCase
{
    public function testReturnsZeroWhenEqualWithinScale(): void
    {
        $function = new BcCompFunction(new FloatLiteral(1.10), new FloatLiteral(1.1), new IntLiteral(2));

        self::assertSame(0, $function());
    }

    public function testReturnsMinusOneWhenLeftIsSmaller(): void
    {
        $function = new BcCompFunction(new IntLiteral(1), new IntLiteral(2));

        self::assertSame(-1, $function());
    }

    public function testReturnsOneWhenLeftIsGreater(): void
    {
        $function = new BcCompFunction(new IntLiteral(2), new IntLiteral(1));

        self::assertSame(1, $function());
    }
}
