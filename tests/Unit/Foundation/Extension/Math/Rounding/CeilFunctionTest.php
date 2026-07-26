<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\CeilFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class CeilFunctionTest extends TestCase
{
    public function testRoundsUpToTheNearestInteger(): void
    {
        $function = new CeilFunction(new SpyExpression(4.2));

        self::assertSame(5.0, $function());
    }

    public function testLeavesAnAlreadyIntegerValueUnchanged(): void
    {
        $function = new CeilFunction(new SpyExpression(4.0));

        self::assertSame(4.0, $function());
    }
}
