<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Rounding\CeilFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class CeilFunctionTest extends TestCase
{
    public function testRoundsUpToTheNearestInteger(): void
    {
        $function = new CeilFunction(new NumericSpyExpression(4.2));

        self::assertSame(5.0, $function());
    }

    public function testLeavesAnAlreadyIntegerValueUnchanged(): void
    {
        $function = new CeilFunction(new NumericSpyExpression(4.0));

        self::assertSame(4.0, $function());
    }
}
