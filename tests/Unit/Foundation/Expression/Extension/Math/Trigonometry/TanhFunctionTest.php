<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\TanhFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class TanhFunctionTest extends TestCase
{
    public function testComputesTanhFunction(): void
    {
        $function = new TanhFunction(new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.7615941559557649, $result, 1e-9);
    }
}
