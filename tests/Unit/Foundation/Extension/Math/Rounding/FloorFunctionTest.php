<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\FloorFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class FloorFunctionTest extends TestCase
{
    public function testRoundsDownToTheNearestInteger(): void
    {
        $function = new FloorFunction(new NumericSpyExpression(4.8));

        self::assertSame(4.0, $function());
    }

    public function testLeavesAnAlreadyIntegerValueUnchanged(): void
    {
        $function = new FloorFunction(new NumericSpyExpression(4.0));

        self::assertSame(4.0, $function());
    }
}
