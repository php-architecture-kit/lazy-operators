<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Exponential;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\PowFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class PowFunctionTest extends TestCase
{
    public function testComputesPowFunction(): void
    {
        $function = new PowFunction(new NumericSpyExpression(2), new NumericSpyExpression(10));

        $result = $function();

        self::assertSame(1024, $result);
    }
}
