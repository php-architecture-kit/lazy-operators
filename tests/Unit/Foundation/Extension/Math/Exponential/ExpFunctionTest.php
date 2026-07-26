<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Exponential;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\ExpFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class ExpFunctionTest extends TestCase
{
    public function testComputesExpFunction(): void
    {
        $function = new ExpFunction(new SpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(2.718281828459045, $result, 1e-9);
    }
}
