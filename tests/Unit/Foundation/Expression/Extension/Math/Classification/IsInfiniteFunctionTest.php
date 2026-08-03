<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Classification;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Classification\IsInfiniteFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class IsInfiniteFunctionTest extends TestCase
{
    public function testComputesIsInfiniteFunction(): void
    {
        $function = new IsInfiniteFunction(new NumericSpyExpression(1.5));

        $result = $function();

        self::assertFalse($result);
    }
}
