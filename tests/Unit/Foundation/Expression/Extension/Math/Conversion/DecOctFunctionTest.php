<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\DecOctFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class DecOctFunctionTest extends TestCase
{
    public function testComputesDecOctFunction(): void
    {
        $function = new DecOctFunction(new NumericSpyExpression(8));

        self::assertSame('10', $function());
    }
}
