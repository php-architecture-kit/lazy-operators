<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\SinFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class SinFunctionTest extends TestCase
{
    public function testComputesSinFunction(): void
    {
        $function = new SinFunction(new SpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.8414709848078965, $result, 1e-9);
    }
}
