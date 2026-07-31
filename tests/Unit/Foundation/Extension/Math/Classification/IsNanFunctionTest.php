<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Classification;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsNanFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class IsNanFunctionTest extends TestCase
{
    public function testComputesIsNanFunction(): void
    {
        $function = new IsNanFunction(new NumericSpyExpression(1.5));

        $result = $function();

        self::assertFalse($result);
    }
}
