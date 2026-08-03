<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\DecBinFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class DecBinFunctionTest extends TestCase
{
    public function testComputesDecBinFunction(): void
    {
        $function = new DecBinFunction(new NumericSpyExpression(5));

        self::assertSame('101', $function());
    }
}
