<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random\LcgValueFunction;
use PHPUnit\Framework\TestCase;

final class LcgValueFunctionTest extends TestCase
{
    public function testReturnsAFloatInTheZeroToOneRange(): void
    {
        $function = new LcgValueFunction();

        for ($i = 0; $i < 20; $i++) {
            $result = $function();

            self::assertIsFloat($result);
            self::assertGreaterThanOrEqual(0.0, $result);
            self::assertLessThan(1.0, $result);
        }
    }
}
