<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BinDecFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class BinDecFunctionTest extends TestCase
{
    public function testComputesBinDecFunction(): void
    {
        $function = new BinDecFunction(new SpyExpression('101'));

        self::assertSame(5, $function());
    }
}
