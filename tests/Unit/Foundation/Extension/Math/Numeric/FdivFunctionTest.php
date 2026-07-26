<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Numeric;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\FdivFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class FdivFunctionTest extends TestCase
{
    public function testComputesFdivFunction(): void
    {
        $function = new FdivFunction(new SpyExpression(10.0), new SpyExpression(4.0));

        $result = $function();

        self::assertEqualsWithDelta(2.5, $result, 1e-9);
    }

    public function testFdivByZeroIsInfinityRatherThanAnException(): void
    {
        $function = new FdivFunction(new SpyExpression(1.0), new SpyExpression(0.0));

        self::assertInfinite($function());
    }
}
