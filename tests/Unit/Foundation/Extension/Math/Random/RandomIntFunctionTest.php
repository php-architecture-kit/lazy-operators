<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandomIntFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class RandomIntFunctionTest extends TestCase
{
    public function testReturnsAnIntegerWithinTheGivenBounds(): void
    {
        $function = new RandomIntFunction(new SpyExpression(1), new SpyExpression(10));

        for ($i = 0; $i < 20; $i++) {
            $result = $function();

            self::assertIsInt($result);
            self::assertGreaterThanOrEqual(1, $result);
            self::assertLessThanOrEqual(10, $result);
        }
    }
}
