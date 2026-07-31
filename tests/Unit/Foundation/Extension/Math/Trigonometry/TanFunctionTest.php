<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\TanFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class TanFunctionTest extends TestCase
{
    public function testComputesTanFunction(): void
    {
        $function = new TanFunction(new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(1.5574077246549023, $result, 1e-9);
    }
}
