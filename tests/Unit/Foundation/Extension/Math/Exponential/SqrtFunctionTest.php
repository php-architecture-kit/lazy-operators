<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Exponential;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\SqrtFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class SqrtFunctionTest extends TestCase
{
    public function testComputesSqrtFunction(): void
    {
        $function = new SqrtFunction(new SpyExpression(4.0));

        $result = $function();

        self::assertEqualsWithDelta(2.0, $result, 1e-9);
    }

    public function testSqrtOfANegativeNumberIsNan(): void
    {
        $function = new SqrtFunction(new SpyExpression(-1.0));

        self::assertNan($function());
    }
}
