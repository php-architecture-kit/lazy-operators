<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class RandFunctionTest extends TestCase
{
    public function testReturnsAnIntegerWithinTheGivenBounds(): void
    {
        $function = new RandFunction(new SpyExpression(1), new SpyExpression(10));

        for ($i = 0; $i < 20; $i++) {
            $result = $function();

            self::assertIsInt($result);
            self::assertGreaterThanOrEqual(1, $result);
            self::assertLessThanOrEqual(10, $result);
        }
    }

    public function testReturnsAnIntegerWhenBoundsAreOmitted(): void
    {
        $function = new RandFunction();

        self::assertIsInt($function());
    }
}
