<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\FloorFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class FloorFunctionTest extends TestCase
{
    public function testRoundsDownToTheNearestInteger(): void
    {
        $function = new FloorFunction(new SpyExpression(4.8));

        self::assertSame(4.0, $function());
    }

    public function testLeavesAnAlreadyIntegerValueUnchanged(): void
    {
        $function = new FloorFunction(new SpyExpression(4.0));

        self::assertSame(4.0, $function());
    }
}
