<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BaseConvertFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class BaseConvertFunctionTest extends TestCase
{
    public function testConvertsHexadecimalToBinary(): void
    {
        $function = new BaseConvertFunction(new SpyExpression('ff'), new SpyExpression(16), new SpyExpression(2));

        self::assertSame('11111111', $function());
    }

    public function testConvertsBinaryToDecimal(): void
    {
        $function = new BaseConvertFunction(new SpyExpression('101'), new SpyExpression(2), new SpyExpression(10));

        self::assertSame('5', $function());
    }
}
