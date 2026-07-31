<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Comparison\BcCompFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\StringLiteral;
use PHPUnit\Framework\TestCase;

final class BcCompFunctionTest extends TestCase
{
    public function testReturnsZeroWhenEqualWithinScale(): void
    {
        $function = new BcCompFunction(new StringLiteral('1.10'), new StringLiteral('1.1'), 2);

        self::assertSame(0, $function());
    }

    public function testReturnsMinusOneWhenLeftIsSmaller(): void
    {
        $function = new BcCompFunction(new StringLiteral('1'), new StringLiteral('2'));

        self::assertSame(-1, $function());
    }

    public function testReturnsOneWhenLeftIsGreater(): void
    {
        $function = new BcCompFunction(new StringLiteral('2'), new StringLiteral('1'));

        self::assertSame(1, $function());
    }
}
