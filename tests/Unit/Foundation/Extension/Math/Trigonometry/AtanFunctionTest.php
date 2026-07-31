<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AtanFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class AtanFunctionTest extends TestCase
{
    public function testComputesAtanFunction(): void
    {
        $function = new AtanFunction(new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.7853981633974483, $result, 1e-9);
    }
}
