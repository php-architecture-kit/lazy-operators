<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\AsinhFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class AsinhFunctionTest extends TestCase
{
    public function testComputesAsinhFunction(): void
    {
        $function = new AsinhFunction(new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.881373587019543, $result, 1e-9);
    }
}
