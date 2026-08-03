<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Exponential;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\HypotFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class HypotFunctionTest extends TestCase
{
    public function testComputesHypotFunction(): void
    {
        $function = new HypotFunction(new NumericSpyExpression(3.0), new NumericSpyExpression(4.0));

        $result = $function();

        self::assertEqualsWithDelta(5.0, $result, 1e-9);
    }
}
