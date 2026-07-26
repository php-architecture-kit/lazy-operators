<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Deg2RadFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class Deg2RadFunctionTest extends TestCase
{
    public function testComputesDeg2RadFunction(): void
    {
        $function = new Deg2RadFunction(new SpyExpression(180.0));

        $result = $function();

        self::assertEqualsWithDelta(3.141592653589793, $result, 1e-9);
    }
}
