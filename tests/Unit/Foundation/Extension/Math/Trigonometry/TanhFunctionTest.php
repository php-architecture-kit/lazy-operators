<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\TanhFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class TanhFunctionTest extends TestCase
{
    public function testComputesTanhFunction(): void
    {
        $function = new TanhFunction(new SpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.7615941559557649, $result, 1e-9);
    }
}
