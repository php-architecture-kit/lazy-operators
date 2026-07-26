<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\LogFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class LogFunctionTest extends TestCase
{
    public function testDefaultsToTheNaturalLogarithmWhenBaseIsOmitted(): void
    {
        $function = new LogFunction(new SpyExpression(M_E));

        self::assertEqualsWithDelta(1.0, $function(), 1e-9);
    }

    public function testUsesTheGivenBase(): void
    {
        $function = new LogFunction(new SpyExpression(100.0), new SpyExpression(10.0));

        self::assertEqualsWithDelta(2.0, $function(), 1e-9);
    }

    public function testLogOfZeroIsNegativeInfinity(): void
    {
        $function = new LogFunction(new SpyExpression(0.0));

        $result = $function();

        self::assertTrue(is_infinite($result));
        self::assertLessThan(0, $result);
    }
}
