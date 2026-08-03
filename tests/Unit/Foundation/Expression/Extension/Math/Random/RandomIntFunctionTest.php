<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random\RandomIntFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class RandomIntFunctionTest extends TestCase
{
    public function testReturnsAnIntegerWithinTheGivenBounds(): void
    {
        $function = new RandomIntFunction(new NumericSpyExpression(1), new NumericSpyExpression(10));

        for ($i = 0; $i < 20; $i++) {
            $result = $function();

            self::assertIsInt($result);
            self::assertGreaterThanOrEqual(1, $result);
            self::assertLessThanOrEqual(10, $result);
        }
    }
}
