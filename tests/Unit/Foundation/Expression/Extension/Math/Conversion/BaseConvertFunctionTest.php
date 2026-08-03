<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\BaseConvertFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PhpArchitecture\LazyOperators\Tests\Support\StringSpyExpression;
use PHPUnit\Framework\TestCase;

final class BaseConvertFunctionTest extends TestCase
{
    public function testConvertsHexadecimalToBinary(): void
    {
        $function = new BaseConvertFunction(new StringSpyExpression('ff'), new NumericSpyExpression(16), new NumericSpyExpression(2));

        self::assertSame('11111111', $function());
    }

    public function testConvertsBinaryToDecimal(): void
    {
        $function = new BaseConvertFunction(new StringSpyExpression('101'), new NumericSpyExpression(2), new NumericSpyExpression(10));

        self::assertSame('5', $function());
    }
}
