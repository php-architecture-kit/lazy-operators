<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Support;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\NumberValueToPrecisionAdapter;
use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PHPUnit\Framework\TestCase;

final class NumberValueToPrecisionAdapterTest extends TestCase
{
    public function testInvokeDelegatesToTheWrappedExpression(): void
    {
        $adapter = new NumberValueToPrecisionAdapter(new IntLiteral(5));

        self::assertSame(5, $adapter());
    }

    public function testBcValueLazilyBridgesTheWrappedExpressionIntoANativeNumber(): void
    {
        $adapter = new NumberValueToPrecisionAdapter(new FloatLiteral(1.5));

        self::assertSame('1.5', (string) $adapter->bcValue());
    }
}
