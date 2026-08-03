<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Numeric;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\FmodFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class FmodFunctionTest extends TestCase
{
    public function testComputesFmodFunction(): void
    {
        $function = new FmodFunction(new NumericSpyExpression(10.0), new NumericSpyExpression(3.0));

        $result = $function();

        self::assertEqualsWithDelta(1.0, $result, 1e-9);
    }

    public function testFmodByZeroIsNanRatherThanAnException(): void
    {
        $function = new FmodFunction(new NumericSpyExpression(5.0), new NumericSpyExpression(0.0));

        self::assertNan($function());
    }
}
