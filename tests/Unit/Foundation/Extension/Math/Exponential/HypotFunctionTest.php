<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Exponential;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\HypotFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class HypotFunctionTest extends TestCase
{
    public function testComputesHypotFunction(): void
    {
        $function = new HypotFunction(new SpyExpression(3.0), new SpyExpression(4.0));

        $result = $function();

        self::assertEqualsWithDelta(5.0, $result, 1e-9);
    }
}
