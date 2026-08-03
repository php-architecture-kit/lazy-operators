<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\BcMath;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Arithmetic\BcAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\BcMath;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\Comparison\BcCompFunction;
use PHPUnit\Framework\TestCase;

final class BcMathTest extends TestCase
{
    public function testAddWrapsRawScalars(): void
    {
        $expression = BcMath::add('1.1', '2.2', 2);

        self::assertInstanceOf(BcAddFunction::class, $expression);
        self::assertSame(3.3, $expression());
    }

    public function testAddAcceptsANativeBcMathNumberDirectly(): void
    {
        self::assertSame(4.0, BcMath::add('1.50', '2.50', 2)());
    }

    public function testChainingTwoAddCallsPreservesPrecisionThroughBcValue(): void
    {
        $inner = BcMath::add('0.1', '0.2', 10);
        $outer = BcMath::add($inner, '0.3', 10);

        self::assertSame('0.6', (string) $outer->__invoke());
    }

    public function testSubMulDivBuildTheExpectedNodes(): void
    {
        self::assertSame(2, BcMath::sub('5', '3')());
        self::assertSame(12, BcMath::mul('3', '4')());
        self::assertSame(2.5, BcMath::div('10', '4', 1)());
    }

    public function testCompReturnsAPlainNumberValueNode(): void
    {
        $expression = BcMath::comp('2', '1');

        self::assertInstanceOf(BcCompFunction::class, $expression);
        self::assertSame(1, $expression());
    }
}
