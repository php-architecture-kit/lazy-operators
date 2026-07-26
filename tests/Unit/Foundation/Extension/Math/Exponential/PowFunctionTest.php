<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Exponential;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\PowFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class PowFunctionTest extends TestCase
{
    public function testComputesPowFunction(): void
    {
        $function = new PowFunction(new SpyExpression(2), new SpyExpression(10));

        $result = $function();

        self::assertSame(1024, $result);
    }
}
