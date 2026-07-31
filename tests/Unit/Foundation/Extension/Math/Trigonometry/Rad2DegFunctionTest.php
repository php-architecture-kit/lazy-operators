<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Rad2DegFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class Rad2DegFunctionTest extends TestCase
{
    public function testComputesRad2DegFunction(): void
    {
        $function = new Rad2DegFunction(new NumericSpyExpression(3.141592653589793));

        $result = $function();

        self::assertEqualsWithDelta(180.0, $result, 1e-9);
    }
}
