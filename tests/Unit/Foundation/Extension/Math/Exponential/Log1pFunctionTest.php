<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Exponential;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Log1pFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class Log1pFunctionTest extends TestCase
{
    public function testComputesLog1pFunction(): void
    {
        $function = new Log1pFunction(new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.6931471805599453, $result, 1e-9);
    }
}
