<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Classification;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsInfiniteFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class IsInfiniteFunctionTest extends TestCase
{
    public function testComputesIsInfiniteFunction(): void
    {
        $function = new IsInfiniteFunction(new SpyExpression(1.5));

        $result = $function();

        self::assertFalse($result);
    }
}
