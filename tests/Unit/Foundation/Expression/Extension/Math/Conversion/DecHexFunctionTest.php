<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\DecHexFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class DecHexFunctionTest extends TestCase
{
    public function testComputesDecHexFunction(): void
    {
        $function = new DecHexFunction(new NumericSpyExpression(255));

        self::assertSame('ff', $function());
    }
}
