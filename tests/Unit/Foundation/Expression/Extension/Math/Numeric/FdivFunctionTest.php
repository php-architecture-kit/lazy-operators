<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Numeric;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\FdivFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class FdivFunctionTest extends TestCase
{
    public function testComputesFdivFunction(): void
    {
        $function = new FdivFunction(new NumericSpyExpression(10.0), new NumericSpyExpression(4.0));

        $result = $function();

        self::assertEqualsWithDelta(2.5, $result, 1e-9);
    }

    public function testFdivByZeroIsInfinityRatherThanAnException(): void
    {
        $function = new FdivFunction(new NumericSpyExpression(1.0), new NumericSpyExpression(0.0));

        self::assertInfinite($function());
    }
}
