<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\SinFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class SinFunctionTest extends TestCase
{
    public function testComputesSinFunction(): void
    {
        $function = new SinFunction(new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.8414709848078965, $result, 1e-9);
    }
}
