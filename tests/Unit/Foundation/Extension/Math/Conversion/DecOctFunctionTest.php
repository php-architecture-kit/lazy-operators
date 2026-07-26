<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecOctFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class DecOctFunctionTest extends TestCase
{
    public function testComputesDecOctFunction(): void
    {
        $function = new DecOctFunction(new SpyExpression(8));

        self::assertSame('10', $function());
    }
}
