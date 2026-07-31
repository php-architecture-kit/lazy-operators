<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Classification;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsFiniteFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class IsFiniteFunctionTest extends TestCase
{
    public function testComputesIsFiniteFunction(): void
    {
        $function = new IsFiniteFunction(new NumericSpyExpression(1.5));

        $result = $function();

        self::assertTrue($result);
    }
}
