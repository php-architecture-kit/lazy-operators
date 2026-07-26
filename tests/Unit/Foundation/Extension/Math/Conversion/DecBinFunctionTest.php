<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecBinFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class DecBinFunctionTest extends TestCase
{
    public function testComputesDecBinFunction(): void
    {
        $function = new DecBinFunction(new SpyExpression(5));

        self::assertSame('101', $function());
    }
}
