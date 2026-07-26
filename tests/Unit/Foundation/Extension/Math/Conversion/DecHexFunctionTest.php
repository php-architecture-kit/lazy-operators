<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecHexFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class DecHexFunctionTest extends TestCase
{
    public function testComputesDecHexFunction(): void
    {
        $function = new DecHexFunction(new SpyExpression(255));

        self::assertSame('ff', $function());
    }
}
